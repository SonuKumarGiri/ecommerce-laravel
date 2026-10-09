@extends('admin.layouts.app')

@section('title', 'Orders')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-800">Manage Orders</h2>
            <p class="text-sm text-gray-500 mt-1">Track and manage customer orders.</p>
        </div>
        
        <!-- Filter Form -->
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row flex-wrap items-center gap-2 w-full md:w-auto">
            <input type="text" name="search" placeholder="Search order, name, email, or mobile..." value="{{ request('search') }}" class="w-full sm:w-64 rounded-lg border-gray-300 text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
            <select name="status" class="w-full sm:w-auto rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2">
                <option value="">All Statuses</option>
                <option value="placed" {{ request('status') == 'placed' ? 'selected' : '' }}>Placed</option>
                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            
            <select name="payment_status" class="w-full sm:w-auto rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2">
                <option value="">All Payments</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
            
            <button type="submit" class="w-full sm:w-auto bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                Filter
            </button>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Order #</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Customer</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Amount</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Status</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Payment</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Date</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($orders as $order)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4 font-bold text-indigo-600">#{{ $order->order_number }}</td>
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">{{ $order->user->name ?? 'Guest' }}</div>
                        <div class="text-xs text-gray-500">{{ $order->user->email ?? '' }}</div>
                    </td>
                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($order->total_amount, 2) }}</td>
                    <td class="px-6 py-4">
                        @php
                            $statusColors = [
                                'placed' => 'bg-blue-100 text-blue-800',
                                'processing' => 'bg-yellow-100 text-yellow-800',
                                'shipped' => 'bg-indigo-100 text-indigo-800',
                                'delivered' => 'bg-green-100 text-green-800',
                                'cancelled' => 'bg-red-100 text-red-800',
                            ];
                            $color = $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $color }}">
                            {{ $order->status }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $payColors = [
                                'pending' => 'bg-yellow-100 text-yellow-800',
                                'paid' => 'bg-green-100 text-green-800',
                                'failed' => 'bg-red-100 text-red-800',
                            ];
                            $pColor = $payColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium uppercase {{ $pColor }}">
                            {{ $order->payment_status }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">
                        {{ $order->created_at->format('M d, Y h:i A') }}
                    </td>
                    <td class="px-6 py-4 text-right space-x-2">
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 hover:text-blue-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                        
                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        <p class="text-lg font-medium text-gray-900">No orders found</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $orders->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
