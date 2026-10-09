<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Throwable;

class CartService
{
    /**
     * Get or create the cart for the current user or guest.
     */
    public static function getCart(?int $userId = null, ?string $sessionId = null): Cart
    {
        $userId = $userId ?: auth()->id();
        $sessionId = $sessionId ?: Session::getId();

        if ($userId) {
            self::migrateGuestCart($userId, $sessionId);
            return Cart::firstOrCreate(['user_id' => $userId]);
        }

        // Guest user - queue cookie to track guest cart across session regenerations
        Cookie::queue('guest_cart_session', $sessionId, 60 * 24 * 7);

        return Cart::firstOrCreate(['session_id' => $sessionId]);
    }

    /**
     * Migrate guest cart items to the authenticated user's cart.
     */
    public static function migrateGuestCart(int $userId, ?string $guestSessionId = null): void
    {
        try {
            $candidateSessionIds = array_filter(array_unique([
                $guestSessionId,
                request()->cookie('guest_cart_session'),
                Session::getId(),
            ]));

            if (empty($candidateSessionIds)) {
                return;
            }

            $guestCarts = Cart::with('items.product')
                ->whereNull('user_id')
                ->whereIn('session_id', $candidateSessionIds)
                ->get();

            if ($guestCarts->isEmpty()) {
                return;
            }

            $userCart = Cart::firstOrCreate(['user_id' => $userId]);

            foreach ($guestCarts as $guestCart) {
                foreach ($guestCart->items as $item) {
                    $existingItem = $userCart->items()->where('product_id', $item->product_id)->first();

                    if ($existingItem) {
                        $newQty = $existingItem->quantity + $item->quantity;
                        if ($item->product && $newQty > $item->product->stock) {
                            $newQty = $item->product->stock;
                        }
                        $existingItem->update(['quantity' => $newQty]);
                        $item->delete();
                    } else {
                        $qty = $item->quantity;
                        if ($item->product && $qty > $item->product->stock) {
                            $qty = $item->product->stock;
                        }
                        $item->update([
                            'cart_id' => $userCart->id,
                            'quantity' => $qty,
                        ]);
                    }
                }

                $guestCart->delete();
            }

            // Forget the guest cart session cookie once migrated
            Cookie::queue(Cookie::forget('guest_cart_session'));

            Log::info('Guest cart migrated to user', [
                'user_id' => $userId,
                'migrated_from_sessions' => $candidateSessionIds,
            ]);
        } catch (Throwable $e) {
            Log::error('Error migrating guest cart: ' . $e->getMessage(), [
                'user_id' => $userId,
                'exception' => $e,
            ]);
        }
    }
}
