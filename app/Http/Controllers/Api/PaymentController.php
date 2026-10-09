<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProcessPaymentRequest;
use App\Http\Resources\OrderResource;

class PaymentController extends Controller
{
    public function process(ProcessPaymentRequest $request)
    {
        try {

            $order = Order::where('user_id', $request->user()->id)->find($request->order_id);

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found or unauthorized.'
                ], 400);
            }

            if (strtolower($order->status) !== 'pending') {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment cannot be processed for this order status.'
                ], 400);
            }

            if ((float) $order->total_amount !== (float) $request->amount) {
                return response()->json([
                    'status' => false,
                    'message' => 'Payment amount does not match order total.'
                ], 400);
            }

            // Simulate 90% success rate
            $isSuccess = rand(1, 100) <= 90;

            DB::beginTransaction();

            if ($isSuccess) {
                $order->update([
                    'status' => 'CONFIRMED',
                    'payment_status' => 'paid'
                ]);

                // Update payment record if exists
                if ($order->payment) {
                    $order->payment->update(['status' => 'SUCCESS']);
                }

                DB::commit();

                Log::info('Payment processed successfully via API', ['order_id' => $order->id, 'amount' => $request->amount]);

                return response()->json([
                    'status' => true,
                    'message' => 'Payment processed successfully',
                    'data' => [
                        'payment_status' => 'SUCCESS',
                        'order' => new OrderResource($order->fresh(['items.product', 'user']))
                    ]
                ], 200);

            } else {
                // Payment Failed Simulation
                $order->update([
                    'status' => 'CANCELLED',
                    'payment_status' => 'failed'
                ]);
                
                // Update payment record if exists
                if ($order->payment) {
                    $order->payment->update(['status' => 'FAILED']);
                }

                // Restore stock
                foreach ($order->items as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                DB::commit();

                Log::warning('Payment failed via API', ['order_id' => $order->id, 'amount' => $request->amount]);

                return response()->json([
                    'status' => false,
                    'message' => 'Payment failed. Order has been cancelled.',
                    'data' => [
                        'payment_status' => 'FAILED',
                        'order' => new OrderResource($order->fresh(['items.product', 'user']))
                    ]
                ], 400);
            }

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API Error in PaymentController@process: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while processing payment.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
