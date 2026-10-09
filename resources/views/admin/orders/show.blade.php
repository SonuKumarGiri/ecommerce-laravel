@extends('admin.layouts.app')

@section('title', 'Order Details')

@section('content')
<div class="max-w-5xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Order #{{ $order->order_number }}</h2>
        <div class="flex gap-3">
            <a href="{{ route('admin.orders.index') }}" class="bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-5 py-2.5 rounded-lg text-sm font-medium transition-colors">
                Back to List
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-slate-50/50">
                    <h3 class="font-bold text-gray-800">Order Items</h3>
                </div>
                <div class="divide-y divide-gray-100">
                    @foreach($order->items as $item)
                    <div class="p-5 flex items-center justify-between hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center text-gray-400 overflow-hidden">
                                @if($item->product && $item->product->image)
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-800">{{ $item->product->name ?? 'Unknown Product' }}</h4>
                                <p class="text-sm text-gray-500">Qty: {{ $item->quantity }} x {{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($item->price, 2) }}</p>
                            </div>
                        </div>
                        <div class="font-bold text-gray-900">
                            {{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($item->quantity * $item->price, 2) }}
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="p-5 bg-slate-50 border-t border-gray-100 flex justify-between items-center">
                    <span class="font-bold text-gray-600 uppercase tracking-wider text-sm">Total Amount</span>
                    <span class="text-2xl font-black text-indigo-600">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($order->total_amount, 2) }}</span>
                </div>
            </div>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Summary & Update Form -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-slate-50/50">
                    <h3 class="font-bold text-gray-800">Order Status</h3>
                </div>
                <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="p-5 space-y-4">
                    @csrf
                    @method('PUT')
                    
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-500">Date</span>
                        <span class="font-medium text-gray-800">{{ $order->created_at->format('M d, Y') }}</span>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Order Status</label>
                            <select name="status" id="status" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm bg-gray-50">
                                @foreach(['PLACED', 'CONFIRMED', 'PROCESSING', 'SHIPPED', 'DELIVERED', 'CANCELLED'] as $status)
                                    <option value="{{ $status }}" {{ old('status', $order->status) === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div>
                            <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-1">Payment Status</label>
                            <select name="payment_status" id="payment_status" required
                                class="w-full text-sm rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm bg-gray-50">
                                @foreach(['PENDING', 'SUCCESS', 'FAILED', 'REFUNDED'] as $pStatus)
                                    <option value="{{ $pStatus }}" {{ old('payment_status', $order->payment_status) === $pStatus ? 'selected' : '' }}>
                                        {{ $pStatus }}
                                    </option>
                                @endforeach
                            </select>
                            @error('payment_status')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                        </div>

                        <div class="pt-3 border-t border-gray-100">
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2.5 rounded-lg font-medium shadow-sm transition-colors flex justify-center items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                Update Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Shipping -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-slate-50/50">
                    <h3 class="font-bold text-gray-800">Shipping Details</h3>
                </div>
                <div class="p-5">
                    <p class="font-bold text-gray-800">{{ $order->shipping_name }}</p>
                    <p class="text-gray-600 mt-1">{{ $order->shipping_mobile }}</p>
                    <p class="text-gray-600 mt-3">{{ $order->shipping_address }}</p>
                    <p class="text-gray-600">{{ $order->shipping_city }}, {{ $order->shipping_pincode }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
