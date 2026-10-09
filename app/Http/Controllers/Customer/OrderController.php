<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('customer.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->where('user_id', auth()->id())->findOrFail($id);
        return view('customer.orders.show', compact('order'));
    }

    public function cancel($id)
    {
        $order = Order::with('items.product')->where('user_id', auth()->id())->findOrFail($id);
        
        if (!in_array(strtolower($order->status), ['placed', 'confirmed', 'processing', 'pending'])) {
            return back()->with('error', 'This order cannot be cancelled at this stage.');
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->update(['status' => 'CANCELLED']);

            // Restore stock
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->increment('stock', $item->quantity);
                }
            }

            // Update payment status if online
            $payment = Payment::where('order_id', $order->id)->first();
            if ($payment && $payment->status === 'SUCCESS') {
                $payment->update(['status' => 'REFUNDED']);
                $order->update(['payment_status' => 'REFUNDED']);
            } else if ($order->payment_status === 'SUCCESS') {
                $order->update(['payment_status' => 'REFUNDED']);
            }

            DB::commit();
            return back()->with('success', 'Order has been cancelled successfully.');
            
        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Order Cancel Error: ' . $th->getMessage());
            return back()->with('error', 'Failed to cancel order.');
        }
    }
}