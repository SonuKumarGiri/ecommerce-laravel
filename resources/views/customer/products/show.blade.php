@extends('customer.layouts.app')

@section('title', $product->name . ' - ShopEase')

@section('content')
<div class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <!-- Breadcrumb -->
        <nav class="text-sm font-medium text-slate-500 mb-8">
            <ol class="list-none p-0 flex flex-wrap items-center gap-y-2">
                <li class="flex items-center"><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="flex items-center"><a href="{{ route('products.index') }}" class="hover:text-indigo-600 transition-colors">Shop</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="flex items-center"><a href="{{ route('products.index', ['category' => $product->category->slug]) }}" class="hover:text-indigo-600 transition-colors">{{ $product->category->name ?? 'Uncategorized' }}</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-indigo-600 truncate">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="lg:grid lg:grid-cols-2 lg:gap-x-12 xl:gap-x-16">
            <!-- Product Image -->
            <div class="flex flex-col">
                <div class="w-full aspect-w-1 aspect-h-1 rounded-2xl bg-slate-100 overflow-hidden flex items-center justify-center border border-slate-200">
                    @if($product->image)
                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="object-cover object-center w-full h-full">
                    @else
                        <svg class="w-24 h-24 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="mt-10 px-4 sm:px-0 lg:mt-0">
                <h1 class="text-3xl font-extrabold tracking-tight text-slate-900">{{ $product->name }}</h1>
                
                <div class="mt-3">
                    <p class="text-3xl font-bold text-indigo-600">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($product->price, 2) }}</p>
                </div>

                <div class="mt-6 border-t border-slate-200 pt-6">
                    <div class="flex items-center gap-2 mb-4">
                        @if($product->stock > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <span class="w-2 h-2 mr-1.5 bg-green-500 rounded-full"></span> In Stock ({{ $product->stock }} available)
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                <span class="w-2 h-2 mr-1.5 bg-red-500 rounded-full"></span> Out of Stock
                            </span>
                        @endif
                    </div>
                    
                    <h3 class="text-lg font-bold text-slate-900 mb-2">Description</h3>
                    <div class="text-base text-slate-600 space-y-4">
                        {!! nl2br(e($product->description)) !!}
                    </div>
                </div>

                <form class="mt-8 border-t border-slate-200 pt-8" action="{{ route('cart.index') }}" method="POST">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    
                    <div class="flex flex-col sm:flex-row sm:items-end gap-4 sm:gap-6" x-data="{ qty: 1, max: {{ $product->stock }} }">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-slate-700 mb-2">Quantity</label>
                            <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden w-32">
                                <button type="button" @click="qty = qty > 1 ? qty - 1 : 1" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-600 hover:bg-slate-100 transition-colors border-r border-slate-300 focus:outline-none" :disabled="qty <= 1">-</button>
                                <input type="number" id="quantity" name="quantity" x-model.number="qty" min="1" :max="max" class="w-12 h-10 text-center border-none focus:ring-0 text-slate-900 font-semibold p-0" readonly>
                                <button type="button" @click="qty = qty < max ? qty + 1 : max" class="w-10 h-10 flex items-center justify-center bg-slate-50 text-slate-600 hover:bg-slate-100 transition-colors border-l border-slate-300 focus:outline-none" :disabled="qty >= max">+</button>
                            </div>
                        </div>

                        <button type="submit" 
                                class="w-full sm:flex-1 bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-indigo-500/30 transition-all flex items-center justify-center gap-2 disabled:bg-slate-400 disabled:shadow-none disabled:cursor-not-allowed"
                                {{ $product->stock <= 0 ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                            {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
                        </button>
                    </div>
                </form>
                
                <!-- Extra Information -->
                <div class="mt-10 grid grid-cols-2 gap-4 border-t border-slate-200 pt-8">
                    <div class="flex items-center gap-3 text-slate-600">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-medium">100% Original Quality</span>
                    </div>
                    <div class="flex items-center gap-3 text-slate-600">
                        <svg class="w-6 h-6 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span class="text-sm font-medium">Free 30 Days Returns</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
        <div class="mt-24">
            <h2 class="text-2xl font-extrabold tracking-tight text-slate-900 mb-8">Related Products</h2>
            <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-4 xl:gap-x-8">
                @foreach($relatedProducts as $related)
                    <div class="group relative bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-xl transition-all duration-300">
                        <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-xl bg-slate-100 mb-4 h-56 flex items-center justify-center relative">
                            @if($related->image)
                                <img src="{{ asset($related->image) }}" alt="{{ $related->name }}" class="object-cover object-center w-full h-full group-hover:scale-105 transition-transform duration-500">
                            @else
                                <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm text-indigo-600 font-medium mb-1">{{ $related->category->name ?? 'Uncategorized' }}</p>
                            <h3 class="text-lg font-bold text-slate-900 mb-1">
                                <a href="{{ route('products.show', $related->slug) }}">
                                    <span aria-hidden="true" class="absolute inset-0 z-0"></span>
                                    {{ $related->name }}
                                </a>
                            </h3>
                            <p class="text-xl font-extrabold text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($related->price, 2) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
