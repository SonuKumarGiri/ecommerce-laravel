@extends('admin.layouts.app')

@section('title', 'All Notifications')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Notifications</h2>
        @if($notifications->count() > 0 && auth()->user()->unreadNotifications->count() > 0)
            <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST">
                @csrf
                <button type="submit" class="bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 hover:text-indigo-600 px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-sm">
                    Mark All as Read
                </button>
            </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="divide-y divide-gray-100">
            @forelse($notifications as $notification)
                <div class="p-6 transition-colors {{ is_null($notification->read_at) ? 'bg-indigo-50/30' : 'hover:bg-gray-50' }} flex flex-col sm:flex-row sm:items-center gap-4 relative">
                    
                    <div class="flex-shrink-0">
                        @if(str_contains($notification->type, 'NewOrderNotification'))
                            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 shadow-sm border border-blue-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            </div>
                        @else
                            <div class="w-12 h-12 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 shadow-sm border border-purple-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1">
                        <h4 class="text-base font-semibold text-gray-900 {{ is_null($notification->read_at) ? '' : 'text-gray-700' }}">
                            {{ $notification->data['message'] ?? 'Notification' }}
                        </h4>
                        <p class="text-sm text-gray-500 mt-1 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $notification->created_at->format('M d, Y h:i A') }} ({{ $notification->created_at->diffForHumans() }})
                        </p>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.notifications.mark-read', $notification->id) }}" class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors">
                            View Details
                        </a>
                    </div>
                    
                    @if(is_null($notification->read_at))
                        <div class="w-3 h-3 rounded-full bg-indigo-500 absolute top-6 right-6 shadow-sm ring-4 ring-white"></div>
                    @endif
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900">No Notifications</h3>
                    <p class="text-gray-500 mt-1">You're all caught up!</p>
                </div>
            @endforelse
        </div>
    </div>
    
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
