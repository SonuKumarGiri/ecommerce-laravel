<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\OrderService;
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
        $order = Order::where('user_id', auth()->id())->findOrFail($id);
        
        if (!OrderService::canCancel($order)) {
            return back()->with('error', 'This order cannot be cancelled at this stage.');
        }

        try {
            OrderService::cancelOrder($order, auth()->user());
            return back()->with('success', 'Order has been cancelled successfully.');
            
        } catch (Throwable $th) {
            Log::error('Order Cancel Error: ' . $th->getMessage());
            return back()->with('error', $th->getMessage() ?: 'Failed to cancel order.');
        }
    }
}