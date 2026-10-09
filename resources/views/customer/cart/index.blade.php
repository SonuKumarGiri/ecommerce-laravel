@extends('customer.layouts.app')

@section('title', 'Your Shopping Cart - ShopEase')

@section('content')
<div class="bg-slate-50 py-10 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <h1 class="text-3xl font-extrabold text-slate-900 mb-8">Shopping Cart</h1>

        @if($cart->items->count() > 0)
            <div class="lg:grid lg:grid-cols-12 lg:gap-x-12 lg:items-start">
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden flex flex-col" style="max-height: calc(100vh - 8rem);">
                        <ul role="list" class="divide-y divide-slate-200 overflow-y-auto" id="cart-items-container" style="scrollbar-width: thin;">
                            @foreach($cart->items as $item)
                                @if($item->product)
                                <li class="p-6 flex py-6 sm:py-8" id="cart-item-{{ $item->id }}">
                                    <div class="flex-shrink-0 w-24 h-24 bg-slate-100 rounded-xl overflow-hidden border border-slate-200 sm:w-32 sm:h-32">
                                        @if($item->product->image)
                                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="w-full h-full object-center object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="ml-4 flex-1 flex flex-col justify-between sm:ml-6">
                                        <div class="relative pr-9 sm:grid sm:grid-cols-2 sm:gap-x-6 sm:pr-0">
                                            <div>
                                                <div class="flex justify-between">
                                                    <h3 class="text-lg font-medium text-slate-900">
                                                        <a href="{{ route('products.show', $item->product->slug) }}" class="hover:text-indigo-600">{{ $item->product->name }}</a>
                                                    </h3>
                                                </div>
                                                <p class="mt-1 text-sm text-slate-500">{{ $item->product->category->name ?? 'Category' }}</p>
                                                <p class="mt-1 text-sm font-medium text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($item->product->price, 2) }}</p>
                                                
                                                <div class="mt-4 flex items-center gap-2">
                                                    @if($item->product->stock > 0)
                                                        <span class="inline-flex items-center text-sm text-green-600">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            In stock
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center text-sm text-red-600">
                                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            Out of stock
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mt-4 sm:mt-0 sm:pr-9 flex items-center justify-between" x-data="cartItem({{ $item->id }}, {{ $item->quantity }}, {{ $item->product->stock }}, {{ $item->product->price }})">
                                                <div class="flex items-center border border-slate-300 rounded-lg overflow-hidden bg-white">
                                                    <button type="button" @click="updateQty(-1)" class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors focus:outline-none" :disabled="qty <= 1 || loading">-</button>
                                                    <input type="number" x-model.number="qty" @change="updateQty(0)" class="w-12 h-8 text-center text-sm border-x border-y-0 border-slate-300 p-0 focus:ring-0 text-slate-900 font-semibold" :disabled="loading">
                                                    <button type="button" @click="updateQty(1)" class="w-8 h-8 flex items-center justify-center text-slate-600 hover:bg-slate-100 transition-colors focus:outline-none" :disabled="qty >= max || loading">+</button>
                                                </div>

                                                <div class="absolute top-0 right-0">
                                                    <form action="{{ route('cart.destroy', $item->id) }}" method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="-m-2 p-2 inline-flex text-slate-400 hover:text-red-500 transition-colors">
                                                            <span class="sr-only">Remove</span>
                                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Order summary -->
                <section class="mt-16 bg-slate-50 rounded-2xl border border-slate-200 px-4 py-6 sm:p-6 lg:p-8 lg:mt-0 lg:col-span-4 lg:sticky lg:top-24">
                    <h2 class="text-lg font-medium text-slate-900">Order summary</h2>

                    <dl class="mt-6 space-y-4">
                        <div class="flex items-center justify-between">
                            <dt class="text-sm text-slate-600">Subtotal</dt>
                            <dd class="text-sm font-medium text-slate-900" id="cart-subtotal">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($subtotal, 2) }}</dd>
                        </div>
                        <div class="flex items-center justify-between border-t border-slate-200 pt-4">
                            <dt class="text-base font-bold text-slate-900">Total</dt>
                            <dd class="text-base font-bold text-indigo-600" id="cart-total">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($subtotal, 2) }}</dd>
                        </div>
                    </dl>

                    <div class="mt-6">
                        <a href="{{ route('checkout.index') }}" class="w-full flex items-center justify-center bg-indigo-600 border border-transparent rounded-lg shadow-lg shadow-indigo-500/30 py-3 px-4 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-slate-50 focus:ring-indigo-500 transition-all gap-2">
                            Proceed to Checkout
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                        </a>
                    </div>
                </section>
            </div>
        @else
            <div class="text-center py-24 bg-white rounded-2xl border border-slate-200 shadow-sm">
                <div class="w-24 h-24 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-6 text-indigo-500">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                </div>
                <h2 class="text-2xl font-bold text-slate-900 mb-2">Your cart is empty</h2>
                <p class="text-slate-500 mb-8 max-w-md mx-auto">Looks like you haven't added anything to your cart yet. Discover our latest products and start shopping.</p>
                <a href="{{ route('products.index') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 shadow-md transition-colors">
                    Start Shopping
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cartItem', (id, initialQty, maxStock, price) => ({
            id: id,
            qty: initialQty,
            max: maxStock,
            price: price,
            loading: false,

            updateQty(change) {
                if (change !== 0) {
                    this.qty += change;
                }
                
                if (this.qty < 1) this.qty = 1;
                if (this.qty > this.max) {
                    this.qty = this.max;
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Requested quantity exceeds available stock.' }}));
                }

                this.loading = true;

                fetch(`/cart/${this.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ quantity: this.qty })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('cart-subtotal').innerText = '{{ $siteSettings['currency_symbol'] ?? '₹' }}' + data.subtotal;
                        document.getElementById('cart-total').innerText = '{{ $siteSettings['currency_symbol'] ?? '₹' }}' + data.subtotal;
                    } else if (data.error) {
                        window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: data.error }}));
                        // Reset to original if failed?
                    }
                })
                .catch(error => {
                    console.error('Error updating cart:', error);
                    window.dispatchEvent(new CustomEvent('toast', { detail: { type: 'error', message: 'Failed to update cart.' }}));
                })
                .finally(() => {
                    this.loading = false;
                });
            }
        }));
    });
</script>
@endpush
