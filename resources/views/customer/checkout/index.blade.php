@extends('customer.layouts.app')

@section('title', 'Checkout - ShopEase')

@section('content')
<div class="bg-slate-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Checkout</h1>

        <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
            <!-- Checkout Form -->
            <div class="lg:col-span-7 xl:col-span-8">
                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8 mb-6">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            Shipping Information
                        </h2>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                            <div class="sm:col-span-2">
                                <label for="name" class="block text-sm font-medium text-slate-700">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
                                @error('name') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-slate-700">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
                                @error('email') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="mobile" class="block text-sm font-medium text-slate-700">Mobile Number</label>
                                <input type="text" id="mobile" name="mobile" value="{{ old('mobile') }}" required class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
                                @error('mobile') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="address" class="block text-sm font-medium text-slate-700">Street Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address') }}" required class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
                                @error('address') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="city" class="block text-sm font-medium text-slate-700">City</label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" required class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
                                @error('city') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-slate-700">State / Province</label>
                                <input type="text" id="state" name="state" value="{{ old('state') }}" required class="mt-1 block w-full border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
                                @error('state') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="sm:col-span-2">
                                <label for="pincode" class="block text-sm font-medium text-slate-700">Postal code / Pincode</label>
                                <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" required class="mt-1 block w-full sm:w-1/2 border-slate-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm transition-shadow">
                                @error('pincode') <span class="text-xs text-red-600 mt-1">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 sm:p-8">
                        <h2 class="text-xl font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                            Payment Method
                        </h2>
                        
                        <div class="space-y-4">
                            <div class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                                <input id="payment-online" name="payment_method" type="radio" value="ONLINE" {{ old('payment_method') == 'ONLINE' ? 'checked' : '' }} class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 cursor-pointer" required>
                                <label for="payment-online" class="ml-3 block text-sm font-medium text-slate-900 flex-1 cursor-pointer">
                                    <span class="block">Credit Card / UPI / Netbanking (Simulated)</span>
                                    <span class="block text-slate-500 font-normal mt-1 text-xs">Secure online payment</span>
                                </label>
                                <svg class="h-6 w-auto text-slate-400" fill="currentColor" viewBox="0 0 36 24"><path d="M32 0H4C1.791 0 0 1.791 0 4v16c0 2.209 1.791 4 4 4h28c2.209 0 4-1.791 4-4V4c0-2.209-1.791-4-4-4zM2.667 9.333h30.666v5.334H2.667V9.333zm0-3.333v-2c0-.735.598-1.333 1.333-1.333h28c.735 0 1.333.598 1.333 1.333v2H2.667zm30.666 14c0 .735-.598 1.333-1.333 1.333H4c-.735 0-1.333-.598-1.333-1.333v-3.333h30.666v3.333zM6.667 17.333h5.333v-1.333H6.667v1.333zm8 0h5.333v-1.333h-5.333v1.333z"/></svg>
                            </div>

                            <div class="flex items-center p-4 border border-slate-200 rounded-xl cursor-pointer hover:border-indigo-300 hover:bg-indigo-50 transition-colors">
                                <input id="payment-cod" name="payment_method" type="radio" value="COD" {{ old('payment_method') == 'COD' ? 'checked' : '' }} class="h-5 w-5 text-indigo-600 focus:ring-indigo-500 border-slate-300 cursor-pointer" required>
                                <label for="payment-cod" class="ml-3 block text-sm font-medium text-slate-900 flex-1 cursor-pointer">
                                    <span class="block">Cash on Delivery</span>
                                    <span class="block text-slate-500 font-normal mt-1 text-xs">Pay with cash upon delivery</span>
                                </label>
                                <svg class="h-6 w-auto text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                        @error('payment_method') <span class="text-xs text-red-600 mt-2 block">{{ $message }}</span> @enderror
                    </div>
                </form>
            </div>

            <!-- Order Summary -->
            <div class="mt-10 lg:mt-0 lg:col-span-5 xl:col-span-4">
                <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-24">
                    <div class="px-6 py-6 sm:px-8 border-b border-slate-200 bg-slate-50/50">
                        <h2 class="text-lg font-bold text-slate-900">Order Summary</h2>
                    </div>
                    
                    <ul role="list" class="divide-y divide-slate-200 px-6 sm:px-8">
                        @foreach($cart->items as $item)
                        <li class="py-6 flex items-center">
                            <div class="flex-shrink-0 w-16 h-16 bg-slate-100 rounded-lg border border-slate-200 overflow-hidden">
                                @if($item->product->image)
                                    <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-center object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-sm font-medium text-slate-900">{{ $item->product->name }}</h3>
                                <p class="text-sm text-slate-500">Qty: {{ $item->quantity }}</p>
                            </div>
                            <p class="text-sm font-medium text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($item->product->price * $item->quantity, 2) }}</p>
                        </li>
                        @endforeach
                    </ul>
                    
                    <div class="px-6 py-6 sm:px-8 bg-slate-50 border-t border-slate-200">
                        <dl class="space-y-3 text-sm text-slate-600">
                            <div class="flex justify-between">
                                <dt>Subtotal</dt>
                                <dd class="font-medium text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($subtotal, 2) }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt>Shipping</dt>
                                <dd class="font-medium text-slate-900">Free</dd>
                            </div>
                            <div class="flex justify-between border-t border-slate-200 pt-3 text-base">
                                <dt class="font-bold text-slate-900">Total</dt>
                                <dd class="font-bold text-indigo-600">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($subtotal, 2) }}</dd>
                            </div>
                        </dl>

                        <button type="submit" form="checkout-form" class="mt-6 w-full bg-indigo-600 border border-transparent rounded-lg shadow-lg shadow-indigo-500/30 py-3 px-4 text-base font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all flex justify-center items-center gap-2">
                            Place Order
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
