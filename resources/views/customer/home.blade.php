@extends('customer.layouts.app')

@section('title', 'ShopEase - Your Premium Shopping Destination')

@section('content')
<!-- Hero Section -->
<div class="relative bg-white overflow-hidden">
    <div class="max-w-7xl mx-auto">
        <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32 pt-20">
            <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                <div class="sm:text-center lg:text-left">
                    <h1 class="text-4xl tracking-tight font-extrabold text-slate-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Premium products</span>
                        <span class="block text-indigo-600 xl:inline">delivered to you</span>
                    </h1>
                    <p class="mt-3 text-base text-slate-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                        Discover our curated collection of high-quality items designed to elevate your everyday life. Fast shipping, secure payments, and exceptional customer service.
                    </p>
                    <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                        <div class="rounded-md shadow">
                            <a href="{{ route('products.index') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg transition-colors shadow-lg shadow-indigo-500/30">
                                Shop Now
                            </a>
                        </div>
                        <div class="mt-3 sm:mt-0 sm:ml-3">
                            <a href="#featured" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-indigo-700 bg-indigo-100 hover:bg-indigo-200 md:py-4 md:text-lg transition-colors">
                                View Featured
                            </a>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-indigo-50 flex items-center justify-center">
        <!-- Abstract Hero Graphic -->
        <div class="relative w-full h-full p-12 hidden lg:flex items-center justify-center">
            <div class="absolute w-72 h-72 bg-indigo-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
            <div class="absolute w-72 h-72 bg-purple-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000 top-20 right-20"></div>
            <div class="absolute w-72 h-72 bg-pink-400 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000 bottom-20 left-20"></div>
            
            <div class="relative grid grid-cols-2 gap-4 transform rotate-12 scale-110 shadow-2xl rounded-2xl bg-white/40 backdrop-blur-sm p-4 border border-white/50">
                <div class="w-40 h-40 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl shadow-inner"></div>
                <div class="w-40 h-40 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-xl shadow-inner mt-8"></div>
                <div class="w-40 h-40 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl shadow-inner -mt-8"></div>
                <div class="w-40 h-40 bg-gradient-to-br from-pink-400 to-orange-400 rounded-xl shadow-inner"></div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products Section -->
<div id="featured" class="bg-slate-50 py-16 sm:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-10">
            <h2 class="text-3xl font-extrabold tracking-tight text-slate-900">Featured Products</h2>
            <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1 transition-colors">
                View all <span aria-hidden="true">&rarr;</span>
            </a>
        </div>

        <div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-4 xl:gap-x-8">
            @forelse($featuredProducts as $product)
                <div class="group relative bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-xl transition-all duration-300">
                    <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-xl bg-slate-100 mb-4 h-56 flex items-center justify-center relative">
                        @if($product->image)
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="object-cover object-center w-full h-full group-hover:scale-105 transition-transform duration-500">
                        @else
                            <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        @endif
                        
                        <!-- Quick Add Button -->
                        <div class="absolute bottom-3 right-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300 z-10">
                            <button onclick="event.preventDefault(); document.getElementById('add-to-cart-{{ $product->id }}').submit();" class="bg-indigo-600 text-white p-2.5 rounded-full shadow-lg hover:bg-indigo-700 transition-colors" title="Add to Cart">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </button>
                            <form id="add-to-cart-{{ $product->id }}" action="{{ route('cart.store') }}" method="POST" class="hidden">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="quantity" value="1">
                            </form>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-indigo-600 font-medium mb-1">{{ $product->category->name ?? 'Uncategorized' }}</p>
                        <h3 class="text-lg font-bold text-slate-900 mb-1">
                            <a href="{{ route('products.show', $product->slug) }}">
                                <span aria-hidden="true" class="absolute inset-0 z-0"></span>
                                {{ $product->name }}
                            </a>
                        </h3>
                        <div class="mt-4 flex items-center justify-between relative z-10">
                            <p class="text-xl font-extrabold text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($product->price, 2) }}</p>
                            @if($product->stock > 0)
                                <button onclick="event.preventDefault(); document.getElementById('add-to-cart-{{ $product->id }}').submit();" class="text-sm font-semibold text-indigo-600 bg-indigo-50 hover:bg-indigo-100 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                    Add
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-12 text-center bg-white rounded-2xl border border-dashed border-slate-300">
                    <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">No products available</h3>
                    <p class="mt-1 text-sm text-slate-500">Check back soon for new arrivals.</p>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Categories Section -->
<div class="bg-white py-16 sm:py-24 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-extrabold tracking-tight text-slate-900 mb-10 text-center">Shop by Category</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-6">
            @foreach($categories as $category)
                <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="group flex flex-col items-center justify-center p-6 bg-slate-50 rounded-2xl border border-slate-100 hover:border-indigo-300 hover:bg-indigo-50 hover:shadow-md transition-all duration-300">
                    <div class="w-12 h-12 rounded-full bg-white flex items-center justify-center shadow-sm text-indigo-600 group-hover:scale-110 transition-transform duration-300 mb-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                    </div>
                    <span class="text-sm font-medium text-slate-900 text-center">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>

<!-- Trust Badges -->
<div class="bg-slate-900 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center text-white">
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mb-4 text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                </div>
                <h4 class="font-bold text-lg mb-1">Fast Delivery</h4>
                <p class="text-slate-400 text-sm">Swift shipping on all orders</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mb-4 text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                </div>
                <h4 class="font-bold text-lg mb-1">Secure Payments</h4>
                <p class="text-slate-400 text-sm">100% secure payment gateway</p>
            </div>
            <div class="flex flex-col items-center">
                <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mb-4 text-indigo-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
                <h4 class="font-bold text-lg mb-1">Easy Returns</h4>
                <p class="text-slate-400 text-sm">30 days return policy</p>
            </div>
        </div>
    </div>
</div>
@endsection
