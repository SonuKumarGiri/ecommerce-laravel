<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

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
        if ($notification->type === 'App\Notifications\NewOrderNotification') {
            return redirect()->route('admin.orders.show', $notification->data['order_id']);
        } elseif ($notification->type === 'App\Notifications\NewUserNotification') {
            return redirect()->route('admin.users.index');
        }

        return back();
    }
}
