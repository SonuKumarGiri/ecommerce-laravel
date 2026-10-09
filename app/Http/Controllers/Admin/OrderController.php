<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateOrderStatusRequest;
use App\Notifications\OrderStatusChangedNotification;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Order::with(['user', 'items.product']);
            
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                      ->orWhere('shipping_name', 'like', "%{$search}%")
                      ->orWhere('shipping_mobile', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }
            if ($request->has('payment_status') && $request->payment_status != '') {
                $query->where('payment_status', $request->payment_status);
            }

            $orders = $query->latest()->paginate(15);
            return view('admin.orders.index', compact('orders'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to retrieve orders: ' . $th->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['user', 'items.product', 'payment'])->findOrFail($id);
            return view('admin.orders.show', compact('order'));
        } catch (\Throwable $th) {
            return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
        }
    }

    public function edit($id)
    {
        try {
            $order = Order::findOrFail($id);
            return view('admin.orders.edit', compact('order'));
        } catch (\Throwable $th) {
            return redirect()->route('admin.orders.index')->with('error', 'Order not found.');
        }
    }

    public function update(UpdateOrderStatusRequest $request, $id)
    {
        try {
            $order = Order::findOrFail($id);

            $oldStatus = strtoupper($order->status ?? '');
            $newStatus = strtoupper($request->status ?? '');

            if ($oldStatus !== 'CANCELLED' && $newStatus === 'CANCELLED') {
                $order = OrderService::cancelOrder($order, auth()->user());

                if ($request->filled('payment_status')) {
                    $order->update(['payment_status' => $request->payment_status]);
                }
            } else {
                $order->update($request->validated());

                if ($oldStatus !== strtoupper($order->status ?? '') && $order->user) {
                    $order->user->notify(new OrderStatusChangedNotification($order));
                }
            }

            return redirect()->route('admin.orders.show', $order->id)->with('success', 'Order updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to update order: ' . $th->getMessage())->withInput();
        }
    }
}
