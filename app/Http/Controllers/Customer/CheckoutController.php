<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use App\Jobs\SendOrderConfirmationJob;
use App\Notifications\NewOrderNotification;
use App\Notifications\CustomerOrderPlacedNotification;
use App\Models\User;
use App\Services\CartService;
use Exception;
use Throwable;

class CheckoutController extends Controller
{
    private function getCart()
    {
        return CartService::getCart(auth()->id())->load('items.product');
    }

    public function index()
    {
        CartService::migrateGuestCart(auth()->id());

        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $subtotal = 0;
        foreach ($cart->items as $item) {
            if ($item->product && $item->product->stock >= $item->quantity) {
                $subtotal += $item->product->price * $item->quantity;
            } else if ($item->product && $item->product->stock > 0) {
                 $subtotal += $item->product->price * $item->product->stock; // adjust dynamically on checkout if needed
            }
        }

        return view('customer.checkout.index', compact('cart', 'subtotal'));
    }

    public function store(CheckoutRequest $request)
    {

        $cart = $this->getCart();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            DB::beginTransaction();

            $totalAmount = 0;

            // Pre-check stock and calculate total strictly on the server
            foreach ($cart->items as $item) {
                // Explicitly lock the product row to prevent race conditions
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product || $product->stock < $item->quantity) {
                    throw new Exception("Product '" . ($product ? $product->name : 'Unknown') . "' does not have enough stock.");
                }

                $itemTotal = round($product->price * $item->quantity, 2);
                $totalAmount += $itemTotal;

                $item->locked_product = $product;
                $item->unit_price = $product->price;
                $item->item_total = $itemTotal;
            }

            $roundedTotal = round($totalAmount, 2);

            // Create Order
            $order = Order::create([
                'order_number' => 'ORD-' . strtoupper(Str::random(10)),
                'user_id' => auth()->id(),
                'total_amount' => $roundedTotal,
                'status' => 'PLACED',
                'payment_status' => 'PENDING',
                'shipping_name' => $request->name,
                'shipping_mobile' => $request->mobile,
                'shipping_address' => $request->address,
                'shipping_city' => $request->city,
                'shipping_pincode' => $request->pincode,
            ]);

            // Create Order Items and Reduce Stock
            foreach ($cart->items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->unit_price,
                    'total' => $item->item_total,
                ]);

                // Reduce stock safely on locked record
                $item->locked_product->decrement('stock', $item->quantity);
            }

            // Create Payment Record (Simulated)
            Payment::create([
                'order_id' => $order->id,
                'amount' => $roundedTotal,
                'payment_method' => $request->payment_method,
                'status' => $request->payment_method === 'COD' ? 'PENDING' : 'SUCCESS', // Simulating successful online payment
                'transaction_id' => $request->payment_method === 'ONLINE' ? 'TXN' . strtoupper(Str::random(12)) : null,
            ]);
            
            // If online payment is successful, update order payment status
            if ($request->payment_method === 'ONLINE') {
                $order->update(['payment_status' => 'SUCCESS']);
            }

            // Clear Cart
            $cart->items()->delete();

            DB::commit();

            // Dispatch Notifications
            try {
                // Notify Customer
                auth()->user()->notify(new CustomerOrderPlacedNotification($order));
                
                // Dispatch Email Job for Customer
                SendOrderConfirmationJob::dispatch($order);

                // Notify Admins
                $admins = User::where('is_admin', true)->get();
                foreach ($admins as $admin) {
                    $admin->notify(new NewOrderNotification($order));
                }
            } catch (\Exception $e) {
                Log::error('Notification Error on Checkout: ' . $e->getMessage());
            }

            return redirect()->route('orders.show', $order->id)->with('success', 'Order placed successfully!');

        } catch (Throwable $th) {
            DB::rollBack();
            Log::error('Checkout Store Error: ' . $th->getMessage());
            $userMessage = 'Failed to place order. Please try again or contact support.';
            
            // If it's our own thrown exception about stock, we can show it safely
            if ($th instanceof \Exception && strpos($th->getMessage(), 'does not have enough stock') !== false) {
                $userMessage = $th->getMessage();
            }

            return redirect()->back()->withInput()->with('error', $userMessage);
        }
    }
}