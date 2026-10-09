<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProductResource;

class CartController extends Controller
{
    private function findOrCreateCart(Request $request)
    {
        $cart = Cart::firstOrCreate(
            ['user_id' => $request->user()->id],
            ['session_id' => null]
        );
        return $cart;
    }

    public function getCart(Request $request)
    {
        try {
            $cart = $this->findOrCreateCart($request);
            $items = $cart->items()->with('product')->get();

            $formattedItems = $items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'cart_id' => $item->cart_id,
                    'quantity' => $item->quantity,
                    'subtotal' => $item->quantity * ($item->product ? $item->product->price : 0),
                    'product' => new ProductResource($item->product)
                ];
            });

            $subtotal = $items->sum(function ($item) {
                return $item->quantity * ($item->product ? $item->product->price : 0);
            });

            Log::info('Cart retrieved successfully via API', ['user_id' => $request->user()->id]);

            return response()->json([
                'status' => true,
                'message' => 'Cart retrieved successfully',
                'data' => [
                    'items' => $formattedItems,
                    'subtotal' => $subtotal,
                    'total' => $subtotal
                ]
            ], 200);
        } catch (\Throwable $e) {
            Log::error('API Error in CartController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving the cart.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function addToCart(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'product_id' => ['required', 'exists:products,id'],
                'quantity' => ['required', 'integer', 'min:1']
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 400);
            }

            $product = Product::find($request->product_id);
            if ($product->stock < $request->quantity) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not enough stock available.'
                ], 400);
            }

            $cart = $this->findOrCreateCart($request);

            $cartItem = $cart->items()->where('product_id', $product->id)->first();

            if ($cartItem) {
                $newQuantity = $cartItem->quantity + $request->quantity;
                if ($product->stock < $newQuantity) {
                    return response()->json([
                        'status' => false,
                        'message' => 'Not enough stock available to add more.'
                    ], 400);
                }
                $cartItem->update(['quantity' => $newQuantity]);
            } else {
                $cart->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $request->quantity
                ]);
            }

            Log::info('Item added to cart via API', ['user_id' => $request->user()->id, 'product_id' => $product->id]);

            return response()->json([
                'status' => true,
                'message' => 'Product added to cart successfully',
                'data' => null
            ], 200);

        } catch (\Throwable $e) {
            Log::error('API Error in CartController@store: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while adding to cart.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateCartItem(Request $request, string $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'quantity' => ['required', 'integer', 'min:1']
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 400);
            }

            $cart = $this->findOrCreateCart($request);
            $cartItem = $cart->items()->with('product')->find($id);

            if (!$cartItem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart item not found.'
                ], 400);
            }

            if ($cartItem->product->stock < $request->quantity) {
                return response()->json([
                    'status' => false,
                    'message' => 'Not enough stock available.'
                ], 400);
            }

            $cartItem->update(['quantity' => $request->quantity]);

            Log::info('Cart item updated via API', ['user_id' => $request->user()->id, 'cart_item_id' => $id]);

            return response()->json([
                'status' => true,
                'message' => 'Cart updated successfully',
                'data' => null
            ], 200);

        } catch (\Throwable $e) {
            Log::error('API Error in CartController@update: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while updating the cart.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function removeCartItem(Request $request, string $id)
    {
        try {
            $cart = $this->findOrCreateCart($request);
            $cartItem = $cart->items()->find($id);

            if (!$cartItem) {
                return response()->json([
                    'status' => false,
                    'message' => 'Cart item not found.'
                ], 400);
            }

            $cartItem->delete();

            Log::info('Cart item removed via API', ['user_id' => $request->user()->id, 'cart_item_id' => $id]);

            return response()->json([
                'status' => true,
                'message' => 'Product removed from cart',
                'data' => null
            ], 200);

        } catch (\Throwable $e) {
            Log::error('API Error in CartController@destroy: ' . $e->getMessage(), [
                'exception' => $e,
                'user_id' => $request->user()?->id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while removing the item.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
