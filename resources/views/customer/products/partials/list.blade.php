<div class="grid grid-cols-1 gap-y-10 sm:grid-cols-2 gap-x-6 lg:grid-cols-3 xl:gap-x-8">
    @forelse($products as $product)
        <div class="group relative bg-white border border-slate-200 rounded-2xl p-4 shadow-sm hover:shadow-xl transition-all duration-300">
            <div class="aspect-w-1 aspect-h-1 w-full overflow-hidden rounded-xl bg-slate-100 mb-4 h-56 flex items-center justify-center relative">
                @if($product->image)
                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="object-cover object-center w-full h-full group-hover:scale-105 transition-transform duration-500">
                @else
                    <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                @endif
                
                @if($product->stock <= 0)
                    <div class="absolute top-3 left-3 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded shadow">OUT OF STOCK</div>
                @endif
                
                <!-- Quick Add Button -->
                @if($product->stock > 0)
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
                @endif
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
        <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-dashed border-slate-300">
            <svg class="mx-auto h-12 w-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            <h3 class="mt-2 text-sm font-medium text-slate-900">No products found</h3>
            <p class="mt-1 text-sm text-slate-500">Try adjusting your search or filters.</p>
        </div>
    @endforelse
</div>

<div class="mt-10 product-pagination hidden" id="infinite-scroll-trigger" data-next-page="{{ $products->nextPageUrl() }}">
    <!-- Hidden trigger for IntersectionObserver -->
    @if($products->hasMorePages())
        <div class="flex justify-center py-6">
            <svg class="animate-spin h-8 w-8 text-indigo-600" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    @endif
</div>
