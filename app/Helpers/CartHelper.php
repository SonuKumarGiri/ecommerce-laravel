<?php

namespace App\Helpers;

use App\Models\CartItem;

class CartHelper
{
    public static function getCartCount()
    {
        return CartItem::whereHas('cart', function($q) {
            if (auth()->check()) {
                $q->where('user_id', auth()->id());
            } else {
                $q->where('session_id', session()->getId());
            }
        })->count();
    }
}