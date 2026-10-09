<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Product;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Jobs\SendOrderConfirmationJob;
use App\Notifications\NewOrderNotification;
use App\Models\User;

class OrderController extends Controller
{
    public function myOrders(Request $request)
    {
        try {
            $orders = Order::with(['items.product'])
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(10);

            Log::info('Orders retrieved successfully via API', [
                'user_id' => $request->user()->id, 
                'count' => $orders->count()
            ]);

            return OrderResource::collection($orders)->additional([
                'status' => true,
                'message' => 'Orders retrieved successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('API Error in OrderController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving orders.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function showMyOrder(Request $request, string $id)
    {
        try {
            $order = Order::with(['items.product', 'user'])
                ->where('user_id', $request->user()->id)
                ->find($id);

            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found or unauthorized.'
                ], 400);
            }

            Log::info('Order retrieved successfully via API', ['order_id' => $id, 'user_id' => $request->user()->id]);

            return response()->json([
                'status' => true,
                'message' => 'Order retrieved successfully',
                'data' => new OrderResource($order)
            ], 200);

        } catch (\Throwable $e) {
            Log::error('API Error in OrderController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'order_id' => $id,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving the order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function placeOrder(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'shipping_name' => ['required', 'string', 'max:255'],
                'shipping_phone' => ['required', 'string', 'max:20'],
                'shipping_address' => ['required', 'string'],
                'shipping_city' => ['required', 'string', 'max:100'],
                'shipping_zip' => ['required', 'string', 'max:20'],
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 400);
            }

            $user = $request->user();
            $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

            if (!$cart || $cart->items->count() === 0) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart is empty.'
                ], 400);
            }

            DB::beginTransaction();

            $totalAmount = 0;
            // First loop: strictly check and lock stock for all items
            foreach ($cart->items as $item) {
                // Explicitly lock the product row to prevent race conditions
                $product = Product::lockForUpdate()->find($item->product_id);
                
                if (!$product || $product->stock < $item->quantity) {
                    DB::rollBack();
                    return response()->json([
                        'status' => false,
                        'message' => 'Not enough stock for ' . ($product ? $product->name : 'one of your items')
                    ], 400);
                }
                
                $totalAmount += $product->price * $item->quantity;
                
                // We map the fresh locked product object to avoid querying it again
                $item->locked_product = $product;
            }

            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(uniqid()),
                'status' => 'PENDING',
                'total_amount' => $totalAmount,
                'shipping_name' => $request->shipping_name,
                'shipping_mobile' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city' => $request->shipping_city,
                'shipping_pincode' => $request->shipping_zip,
            ]);

            foreach ($cart->items as $item) {
                $product = $item->locked_product;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'price' => $product->price,
                    'quantity' => $item->quantity,
                    'total' => $product->price * $item->quantity,
                ]);

                // Decrement safely on the locked record
                $product->decrement('stock', $item->quantity);
            }

            $cart->items()->delete();

            DB::commit();

            Log::info('Order created successfully via API', ['order_id' => $order->id, 'user_id' => $user->id]);

            // Dispatch Background Job to send confirmation notification to customer
            SendOrderConfirmationJob::dispatch($order);

            // Notify all admins about the new order
            $admins = User::where('is_admin', true)->get();
            foreach ($admins as $admin) {
                $admin->notify(new NewOrderNotification($order));
            }

            $order->load(['items.product', 'user']);

            return response()->json([
                'status' => true,
                'message' => 'Order created successfully',
                'data' => new OrderResource($order)
            ], 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            
            Log::error('API Error in OrderController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while creating the order. Please try again.',
            ], 500);
        }
    }

    public function cancelOrder(Request $request, string $id)
    {
        try {
            $order = Order::with('items.product')->where('user_id', $request->user()->id)->find($id);
            
            if (!$order) {
                return response()->json([
                    'status' => false,
                    'message' => 'Order not found.'
                ], 404);
            }

            if (!in_array(strtoupper($order->status), ['PLACED', 'CONFIRMED', 'PROCESSING', 'PENDING'])) {
                return response()->json([
                    'status' => false,
                    'message' => 'This order cannot be cancelled at this stage.'
                ], 400);
            }

            DB::beginTransaction();

            $order->update(['status' => 'cancelled']);

            foreach ($order->items as $item) {
                if ($item->product_id) {
                    Product::where('id', $item->product_id)->increment('stock', $item->quantity);
                }
            }

            $payment = \App\Models\Payment::where('order_id', $order->id)->first();
            if ($payment && strtoupper($payment->status) === 'SUCCESS') {
                $payment->update(['status' => 'REFUNDED']);
                $order->update(['payment_status' => 'refunded']);
            } else if (strtoupper($order->payment_status) === 'SUCCESS' || $order->payment_status === 'paid') {
                $order->update(['payment_status' => 'refunded']);
            } else {
                $order->update(['payment_status' => 'cancelled']);
            }

            DB::commit();

            Log::info('Order cancelled via API', ['order_id' => $order->id, 'user_id' => $request->user()->id]);

            return response()->json([
                'status' => true,
                'message' => 'Order cancelled successfully.'
            ], 200);
            
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('API Error in OrderController@cancelOrder: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to cancel the order.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
