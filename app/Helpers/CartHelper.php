<?php

namespace App\Helpers;

use App\Models\CartItem;

class CartHelper
{
    public static function getCartCount()
    {
        if (auth()->check()) {
            \App\Services\CartService::migrateGuestCart(auth()->id());
            return CartItem::whereHas('cart', function($q) {
                $q->where('user_id', auth()->id());
            })->count();
        }

        $sessionIds = array_filter(array_unique([
            request()->cookie('guest_cart_session'),
            session()->getId(),
        ]));

        return CartItem::whereHas('cart', function($q) use ($sessionIds) {
            $q->whereNull('user_id')->whereIn('session_id', $sessionIds);
        })->count();
    }
}