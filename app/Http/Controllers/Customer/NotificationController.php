<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'All notifications marked as read.');
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        // Redirect based on notification type
        if ($notification->type === 'App\Notifications\OrderStatusChangedNotification' || 
            $notification->type === 'App\Notifications\CustomerOrderPlacedNotification') {
            return redirect()->route('orders.show', $notification->data['order_id']);
        }

        return back();
    }
}
