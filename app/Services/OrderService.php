<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\OrderStatusChangedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;
use Throwable;

class OrderService
{
    /**
     * Check if an order can be cancelled.
     */
    public static function canCancel(Order $order): bool
    {
        $cancellableStatuses = ['PLACED', 'CONFIRMED', 'PROCESSING', 'PENDING'];
        return in_array(strtoupper($order->status), $cancellableStatuses, true);
    }

    /**
     * Cancel an order safely with stock restoration, payment refund, and database transaction.
     *
     * @param Order|int $order
     * @param User|null $cancelledBy
     * @param string|null $paymentStatusOverride
     * @param bool $force
     * @return Order
     * @throws Exception
     */
    public static function cancelOrder($order, ?User $cancelledBy = null, ?string $paymentStatusOverride = null, bool $force = false): Order
    {
        $orderId = $order instanceof Order ? $order->id : $order;

        return DB::transaction(function () use ($orderId, $cancelledBy, $paymentStatusOverride, $force) {
            // Lock order row to prevent concurrent race condition cancellations
            $lockedOrder = Order::with('items.product')->lockForUpdate()->findOrFail($orderId);

            if (strtoupper($lockedOrder->status) === 'CANCELLED') {
                throw new Exception("Order #{$lockedOrder->order_number} is already cancelled.");
            }

            if (!$force && !self::canCancel($lockedOrder)) {
                throw new Exception("Order #{$lockedOrder->order_number} cannot be cancelled at status '{$lockedOrder->status}'.");
            }

            // 1. Restore product stock safely with pessimistic row locking
            foreach ($lockedOrder->items as $item) {
                if ($item->product_id) {
                    $product = Product::lockForUpdate()->find($item->product_id);
                    if ($product) {
                        $product->increment('stock', $item->quantity);
                        Log::info("Restored stock for product during order cancellation", [
                            'order_id' => $lockedOrder->id,
                            'product_id' => $product->id,
                            'restored_quantity' => $item->quantity,
                            'new_stock' => $product->fresh()->stock,
                        ]);
                    }
                }
            }

            // 2. Update payment status & process refund if paid
            $payment = Payment::lockForUpdate()->where('order_id', $lockedOrder->id)->first();
            
            if ($paymentStatusOverride !== null) {
                $paymentStatus = $paymentStatusOverride;
                if ($payment) {
                    $payment->update(['status' => strtoupper($paymentStatusOverride)]);
                }
            } else {
                $paymentStatus = 'CANCELLED';

                if ($payment) {
                    if (in_array(strtoupper($payment->status), ['SUCCESS', 'PAID'], true)) {
                        $payment->update(['status' => 'REFUNDED']);
                        $paymentStatus = 'REFUNDED';
                    } else {
                        $payment->update(['status' => 'CANCELLED']);
                    }
                } elseif (in_array(strtoupper($lockedOrder->payment_status ?? ''), ['SUCCESS', 'PAID'], true)) {
                    $paymentStatus = 'REFUNDED';
                }
            }

            // 3. Update order status
            $lockedOrder->update([
                'status' => 'CANCELLED',
                'payment_status' => $paymentStatus,
            ]);

            Log::info("Order cancelled successfully", [
                'order_id' => $lockedOrder->id,
                'order_number' => $lockedOrder->order_number,
                'payment_status' => $paymentStatus,
                'cancelled_by' => $cancelledBy ? $cancelledBy->id : 'system/customer',
            ]);

            // 4. Send status update notification to the customer
            if ($lockedOrder->user) {
                try {
                    $lockedOrder->user->notify(new OrderStatusChangedNotification($lockedOrder));
                } catch (Throwable $e) {
                    Log::warning("Could not dispatch OrderStatusChangedNotification on cancellation: " . $e->getMessage());
                }
            }

            return $lockedOrder;
        });
    }
}
