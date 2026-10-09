@extends('customer.layouts.app')

@section('title', 'Order ' . $order->order_number . ' - ShopEase')

@section('content')
<div class="bg-slate-50 py-10 min-h-screen">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 sm:mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 flex flex-wrap items-center gap-2 sm:gap-3">
                Order <span class="text-indigo-600 break-all">{{ $order->order_number }}</span>
            </h1>
            <a href="{{ route('orders.index') }}" class="text-slate-500 hover:text-indigo-600 font-medium transition-colors self-start sm:self-auto">&larr; Back to Orders</a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-6 sm:mb-8">
            <div class="p-4 sm:p-6 md:p-8 border-b border-slate-200 bg-slate-50/50 flex flex-col md:flex-row md:justify-between md:items-center gap-4">
                <div>
                    <p class="text-sm text-slate-500 mb-1">Placed on <span class="font-medium text-slate-900">{{ $order->created_at->format('M d, Y h:i A') }}</span></p>
                    <div class="flex items-center gap-3 mt-2">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                            {{ $order->status === 'DELIVERED' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'CANCELLED' ? 'bg-red-100 text-red-800' : '' }}
                            {{ !in_array($order->status, ['DELIVERED', 'CANCELLED']) ? 'bg-blue-100 text-blue-800' : '' }}
                        ">
                            Status: {{ ucfirst(strtolower($order->status)) }}
                        </span>
                        
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full 
                            {{ $order->payment_status === 'SUCCESS' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}
                        ">
                            Payment: {{ $order->payment_status }}
                        </span>
                    </div>
                </div>
                <div class="text-left md:text-right mt-4 md:mt-0 border-t border-slate-200 md:border-0 pt-4 md:pt-0">
                    <p class="text-sm text-slate-500 mb-1">Total Amount</p>
                    <p class="text-2xl font-bold text-indigo-600">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($order->total_amount, 2) }}</p>
                    @if(in_array($order->status, ['PLACED', 'CONFIRMED', 'PROCESSING']) || in_array(strtolower($order->status), ['placed', 'confirmed', 'processing']))
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="mt-4" onsubmit="return confirm('Are you sure you want to cancel this order?');">
                        @csrf
                        <button type="submit" class="w-full md:w-auto text-sm font-medium text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-4 py-2 rounded-lg transition-colors border border-red-100">Cancel Order</button>
                    </form>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <div class="p-0">
                <ul role="list" class="divide-y divide-slate-100">
                    @foreach($order->items as $item)
                        <li class="p-4 sm:p-6 md:p-8 flex flex-col sm:flex-row sm:items-center gap-4 hover:bg-slate-50 transition-colors">
                            <div class="flex items-center gap-4 sm:gap-6 flex-1 w-full">
                                <div class="flex-shrink-0 w-16 h-16 sm:w-20 sm:h-20 bg-slate-100 rounded-lg border border-slate-200 overflow-hidden">
                                    @if($item->product && $item->product->image)
                                        <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-center object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                                            <svg class="w-8 h-8 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm sm:text-base font-bold text-slate-900 truncate">
                                        @if($item->product)
                                            <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-indigo-600 transition-colors">{{ $item->product->name }}</a>
                                        @else
                                            Product Unavailable
                                        @endif
                                    </h3>
                                    <p class="text-xs sm:text-sm text-slate-500 mt-1">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($item->price, 2) }} × {{ $item->quantity }}</p>
                                </div>
                            </div>
                            <div class="text-left sm:text-right mt-2 sm:mt-0 sm:ml-4 w-full sm:w-auto flex justify-between sm:block border-t sm:border-0 border-slate-100 pt-3 sm:pt-0">
                                <span class="sm:hidden text-sm text-slate-500 font-medium">Total:</span>
                                <p class="text-base font-bold text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($item->price * $item->quantity, 2) }}</p>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Shipping Info -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    Shipping Details
                </h3>
                <address class="not-italic text-sm text-slate-600 space-y-1">
                    <p class="font-bold text-slate-900 text-base mb-2">{{ $order->shipping_name }}</p>
                    <p>{{ $order->shipping_address }}</p>
                    <p>{{ $order->shipping_city }}, {{ $order->shipping_pincode }}</p>
                    <p class="pt-2"><strong>Mobile:</strong> {{ $order->shipping_mobile }}</p>
                </address>
            </div>

            <!-- Order Summary -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                <h3 class="text-lg font-bold text-slate-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Summary
                </h3>
                <dl class="space-y-3 text-sm text-slate-600">
                    <div class="flex justify-between">
                        <dt>Subtotal</dt>
                        <dd class="font-medium text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($order->total_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt>Shipping</dt>
                        <dd class="font-medium text-slate-900">Free</dd>
                    </div>
                    <div class="flex justify-between border-t border-slate-200 pt-3 text-base">
                        <dt class="font-bold text-slate-900">Total</dt>
                        <dd class="font-bold text-indigo-600">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($order->total_amount, 2) }}</dd>
                    </div>
                </dl>
            </div>
        </div>

    </div>
</div>
@endsection
