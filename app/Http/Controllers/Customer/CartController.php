<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Throwable;

class CartController extends Controller
{
    private function getCart()
    {
        return CartService::getCart();
    }

    public function index()
    {
        try {
            $cart = $this->getCart();
            $cart->load('items.product');

            $subtotal = 0;
            foreach ($cart->items as $item) {
                if ($item->product) {
                    $subtotal += $item->product->price * $item->quantity;
                }
            }

            return view('customer.cart.index', compact('cart', 'subtotal'));
        } catch (Throwable $th) {
            Log::error('Cart Index Error: ' . $th->getMessage());
            return back()->with('error', 'Something went wrong while loading the cart.');
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1'
            ]);

            $product = Product::findOrFail($request->product_id);
            
            if ($product->stock < $request->quantity) {
                return back()->with('error', 'Requested quantity exceeds available stock.');
            }

            $cart = $this->getCart();

            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                $newQty = $cartItem->quantity + $request->quantity;
                if ($newQty > $product->stock) {
                    return back()->with('error', 'Cannot add more. Exceeds available stock.');
                }
                $cartItem->update(['quantity' => $newQty]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);
            }

            return redirect()->route('cart.index')->with('success', 'Product added to cart!');
        } catch (Throwable $th) {
            Log::error('Cart Add Error: ' . $th->getMessage());
            return back()->with('error', 'Failed to add product to cart.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'quantity' => 'required|integer|min:1'
            ]);

            $cartItem = CartItem::with('product')->findOrFail($id);
            
            // Ensure this item belongs to the current user's cart
            $cart = $this->getCart();
            if ($cartItem->cart_id !== $cart->id) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            if ($cartItem->product->stock < $request->quantity) {
                return response()->json([
                    'error' => 'Only ' . $cartItem->product->stock . ' items left in stock.'
                ], 422);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            // Recalculate totals
            $cart->load('items.product');
            $subtotal = 0;
            foreach ($cart->items as $item) {
                $subtotal += $item->product->price * $item->quantity;
            }

            return response()->json([
                'success' => true,
                'item_total' => number_format($cartItem->product->price * $request->quantity, 2, '.', ''),
                'subtotal' => number_format($subtotal, 2, '.', '')
            ]);
        } catch (Throwable $th) {
            Log::error('Cart Update Error: ' . $th->getMessage());
            return response()->json(['error' => 'Failed to update cart.'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $cartItem = CartItem::findOrFail($id);
            
            $cart = $this->getCart();
            if ($cartItem->cart_id === $cart->id) {
                $cartItem->delete();
            }

            return redirect()->route('cart.index')->with('success', 'Item removed from cart.');
        } catch (Throwable $th) {
            Log::error('Cart Delete Error: ' . $th->getMessage());
            return back()->with('error', 'Failed to remove item from cart.');
        }
    }
}