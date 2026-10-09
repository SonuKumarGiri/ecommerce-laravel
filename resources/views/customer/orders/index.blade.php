@extends('customer.layouts.app')

@section('title', 'My Orders - ShopEase')

@section('content')
<div class="bg-slate-50 py-10 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-8 flex items-center justify-between">
            <h1 class="text-3xl font-extrabold text-slate-900">My Orders</h1>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Continue Shopping &rarr;</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Order Details</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Date</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Total</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="relative px-6 py-4">
                                    <span class="sr-only">View</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-slate-200">
                            @foreach($orders as $order)
                                <tr class="hover:bg-slate-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-slate-900">{{ $order->order_number }}</div>
                                        <div class="text-xs text-slate-500">{{ $order->items()->count() }} items</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-600">
                                        {{ $order->created_at->format('M d, Y h:i A') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-slate-900">
                                        {{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $order->status === 'DELIVERED' ? 'bg-green-100 text-green-800' : '' }}
                                            {{ $order->status === 'CANCELLED' ? 'bg-red-100 text-red-800' : '' }}
                                            {{ !in_array($order->status, ['DELIVERED', 'CANCELLED']) ? 'bg-blue-100 text-blue-800' : '' }}
                                        ">
                                            {{ ucfirst(strtolower($order->status)) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('orders.show', $order->id) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors">View Details</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-slate-200">
                    {{ $orders->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-400">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    </div>
                    <h3 class="text-lg font-medium text-slate-900 mb-1">No orders yet</h3>
                    <p class="text-slate-500 mb-6">When you place an order, it will appear here.</p>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-6 py-2.5 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 transition-colors shadow-sm">
                        Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
