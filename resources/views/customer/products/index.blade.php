@extends('customer.layouts.app')

@section('title', 'Shop Products - ShopEase')

@section('content')
<div class="bg-slate-50 py-10" x-data="productFilter()">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Breadcrumb & Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 pb-6 border-b border-slate-200 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-slate-900">All Products</h1>
                <nav class="text-sm font-medium text-slate-500 mt-2">
                    <ol class="list-none p-0 inline-flex items-center">
                        <li class="flex items-center"><a href="{{ route('home') }}" class="hover:text-indigo-600 transition-colors">Home</a></li>
                        <li><span class="mx-2">/</span></li>
                        <li class="text-indigo-600">Shop</li>
                    </ol>
                </nav>
            </div>
            
            <!-- Mobile Filter Toggle -->
            <button @click="showMobileFilters = !showMobileFilters" class="md:hidden flex items-center gap-2 bg-white px-4 py-2 border border-slate-300 rounded-lg text-sm font-medium text-slate-700 shadow-sm">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filters
            </button>
        </div>

        <div class="flex flex-col md:flex-row gap-8">
            <!-- Sidebar Filters -->
            <div class="w-full md:w-64 flex-shrink-0 md:sticky md:top-8 md:max-h-[calc(100vh-4rem)] md:overflow-y-auto pr-2" :class="showMobileFilters ? 'block' : 'hidden md:block'" style="scrollbar-width: thin;">
                <form id="filter-form" @submit.prevent="fetchProducts">
                    
                    <!-- Reset Filters Header -->
                    <div class="flex items-center justify-between mb-4 px-1">
                        <h2 class="text-lg font-bold text-slate-900">Filters</h2>
                        <button type="button" @click="resetFilters" class="text-sm font-medium text-red-600 hover:text-red-700 hover:bg-red-50 transition-colors px-2 py-1 rounded flex items-center gap-1" title="Reset all filters">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            Reset All
                        </button>
                    </div>

                    <!-- Search -->
                    <div class="mb-8 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
                        <h3 class="font-bold text-slate-900 mb-4">Search</h3>
                        <div class="relative">
                            <input type="text" name="search" value="{{ request('search') }}" @input.debounce.500ms="fetchProducts" placeholder="Search products..." class="w-full pl-10 pr-4 py-2.5 rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm transition-shadow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                        </div>
                    </div>

                    <!-- Categories -->
                    <div class="mb-8 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm" x-data="{ catSearch: '' }">
                        <h3 class="font-bold text-slate-900 mb-4 flex justify-between items-center">
                            Category
                            @php $selectedCats = is_array(request('categories')) ? request('categories') : (request('categories') ? [request('categories')] : []); @endphp
                            @if(count($selectedCats) > 0)
                                <a href="#" @click.prevent="resetCategory" class="text-xs font-normal text-indigo-600 hover:underline">Clear</a>
                            @endif
                        </h3>
                        
                        <div class="mb-3">
                            <input type="text" x-model="catSearch" placeholder="Find category..." class="w-full px-3 py-2 text-sm rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm">
                        </div>

                        <div class="space-y-3 max-h-48 overflow-y-auto pr-2" style="scrollbar-width: thin;">
                            <div class="flex items-center" x-show="catSearch === '' || 'all categories'.includes(catSearch.toLowerCase())">
                                <input type="checkbox" id="cat-all" @change="resetCategory" {{ count($selectedCats) === 0 ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer mt-0.5">
                                <label for="cat-all" class="ml-3 text-sm text-slate-600 cursor-pointer flex-1 flex justify-between items-center">
                                    <span class="font-medium">All Categories</span>
                                </label>
                            </div>
                            @foreach($categories as $category)
                                <div class="flex items-center" x-show="catSearch === '' || '{{ strtolower(str_replace("'", "\'", $category->name)) }}'.includes(catSearch.toLowerCase())">
                                    <input type="checkbox" id="cat-{{ $category->id }}" name="categories[]" value="{{ $category->slug }}" @change="fetchProducts" {{ in_array($category->slug, $selectedCats) ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500 cursor-pointer mt-0.5">
                                    <label for="cat-{{ $category->id }}" class="ml-3 text-sm text-slate-600 cursor-pointer flex-1 flex justify-between items-center">
                                        <span>{{ $category->name }}</span>
                                        <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $category->products_count }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Range -->
                    <div class="mb-8 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm" x-data="{ minPrice: {{ request('min_price', 0) }}, maxPrice: {{ request('max_price', $maxProductPrice) }}, maxSlider: {{ $maxProductPrice }} }" @reset-price.window="minPrice = 0; maxPrice = maxSlider">
                        <h3 class="font-bold text-slate-900 mb-4 flex justify-between items-center">
                            Price Range
                        </h3>
                        
                        <div class="mb-4 mt-2">
                            <div class="flex justify-between items-center text-sm font-medium text-slate-700 mb-3">
                                <span class="bg-slate-100 px-2 py-1 rounded border border-slate-200" x-text="'{{ $siteSettings['currency_symbol'] ?? '₹' }}' + minPrice"></span>
                                <span class="bg-slate-100 px-2 py-1 rounded border border-slate-200" x-text="'{{ $siteSettings['currency_symbol'] ?? '₹' }}' + maxPrice"></span>
                            </div>
                            <div class="relative w-full h-1.5 bg-slate-200 rounded-lg">
                                <div class="absolute h-full bg-indigo-600 rounded-lg" :style="`left: ${(minPrice / maxSlider) * 100}%; right: ${100 - (maxPrice / maxSlider) * 100}%`"></div>
                                <input type="range" name="min_price" x-model="minPrice" @change="if(parseInt(minPrice) > parseInt(maxPrice)) minPrice = maxPrice; fetchProducts()" min="0" :max="maxSlider" step="10" class="absolute w-full -top-2 h-5 appearance-none bg-transparent pointer-events-none cursor-pointer" style="z-index: 20;">
                                <input type="range" name="max_price" x-model="maxPrice" @change="if(parseInt(maxPrice) < parseInt(minPrice)) maxPrice = minPrice; fetchProducts()" min="0" :max="maxSlider" step="10" class="absolute w-full -top-2 h-5 appearance-none bg-transparent pointer-events-none cursor-pointer" style="z-index: 21;">
                            </div>
                        </div>

                        <style>
                            input[type=range]::-webkit-slider-thumb {
                                pointer-events: all;
                                width: 20px;
                                height: 20px;
                                -webkit-appearance: none;
                                appearance: none;
                                background-color: #ffffff;
                                border: 2px solid #4f46e5;
                                border-radius: 50%;
                                cursor: pointer;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                                transition: transform 0.1s;
                            }
                            input[type=range]::-webkit-slider-thumb:hover {
                                transform: scale(1.1);
                            }
                            input[type=range]::-moz-range-thumb {
                                pointer-events: all;
                                width: 20px;
                                height: 20px;
                                background-color: #ffffff;
                                border: 2px solid #4f46e5;
                                border-radius: 50%;
                                cursor: pointer;
                                box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                                transition: transform 0.1s;
                            }
                            input[type=range]::-moz-range-thumb:hover {
                                transform: scale(1.1);
                            }
                        </style>
                    </div>
                </form>
            </div>

            <!-- Product Grid -->
            <div class="flex-1">
                <!-- Top Toolbar -->
                <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm mb-8 flex justify-between items-center">
                    <p class="text-sm text-slate-600">Showing <span class="font-semibold text-slate-900" x-text="totalResults">{{ $products->total() }}</span> results</p>
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm font-medium text-slate-700 hidden sm:block">Sort by:</label>
                        <select id="sort" name="sort" form="filter-form" @change="fetchProducts" class="block w-full pl-3 pr-10 py-2 text-base border-slate-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-lg">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="price_low_high" {{ request('sort') == 'price_low_high' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_high_low" {{ request('sort') == 'price_high_low' ? 'selected' : '' }}>Price: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Loader -->
                <div x-show="loading" class="flex justify-center items-center py-20" x-cloak>
                    <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>

                <!-- Products Container -->
                <div id="product-list-container" x-show="!loading">
                    @include('customer.products.partials.list')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productFilter', () => ({
            showMobileFilters: false,
            loading: false,
            totalResults: '{{ $products->total() }}',
            
            resetCategory() {
                document.querySelectorAll('input[name="categories[]"]').forEach(cb => cb.checked = false);
                this.fetchProducts();
            },
            
            resetFilters() {
                const searchInput = document.querySelector('input[name="search"]');
                if (searchInput) searchInput.value = '';

                document.querySelectorAll('input[name="categories[]"]').forEach(cb => cb.checked = false);

                const sortSelect = document.querySelector('select[name="sort"]');
                if (sortSelect) sortSelect.value = 'newest';

                window.dispatchEvent(new CustomEvent('reset-price'));
                
                this.fetchProducts(window.location.pathname);
            },
            
            observer: null,
            loadingMore: false,

            fetchProducts(url = null, append = false) {
                if (append) {
                    this.loadingMore = true;
                } else {
                    this.loading = true;
                }
                
                // If url is not provided, construct it from form
                let requestUrl = url;
                if (!requestUrl || !append) {
                    const form = document.getElementById('filter-form');
                    const formData = new FormData(form);
                    const params = new URLSearchParams(formData);
                    const targetUrl = url instanceof Event || !url ? window.location.pathname : url;
                    // If appending, url is already correctly formed with page param
                    requestUrl = append ? url : `${targetUrl}?${params.toString()}`;
                    
                    // Manage 'All Categories' Checkbox visual state
                    const allCatCheckbox = document.getElementById('cat-all');
                    if (allCatCheckbox) {
                        const hasCategorySelected = formData.getAll('categories[]').length > 0;
                        allCatCheckbox.checked = !hasCategorySelected;
                    }
                }
                
                // Update URL history without reloading only if not appending
                if (!append) {
                    window.history.pushState({}, '', requestUrl);
                }
                
                fetch(requestUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    const container = document.getElementById('product-list-container');
                    
                    if (append) {
                        const tempDiv = document.createElement('div');
                        tempDiv.innerHTML = html;
                        
                        const newGrid = tempDiv.querySelector('.grid');
                        const existingGrid = container.querySelector('.grid');
                        
                        if (newGrid && existingGrid) {
                            existingGrid.insertAdjacentHTML('beforeend', newGrid.innerHTML);
                        }
                        
                        const newTrigger = tempDiv.querySelector('#infinite-scroll-trigger');
                        const oldTrigger = container.querySelector('#infinite-scroll-trigger');
                        if (newTrigger && oldTrigger) {
                            oldTrigger.replaceWith(newTrigger);
                        }
                    } else {
                        container.innerHTML = html;
                        if(window.innerWidth < 768) {
                            this.showMobileFilters = false;
                        }
                    }
                    
                    this.bindInfiniteScroll();
                })
                .catch(error => console.error('Error fetching products:', error))
                .finally(() => {
                    this.loading = false;
                    this.loadingMore = false;
                });
            },
            
            bindInfiniteScroll() {
                if (this.observer) {
                    this.observer.disconnect();
                }
                
                const trigger = document.getElementById('infinite-scroll-trigger');
                if (!trigger) return;
                
                const nextUrl = trigger.getAttribute('data-next-page');
                
                if (nextUrl && nextUrl.trim() !== '') {
                    trigger.classList.remove('hidden');
                    
                    this.observer = new IntersectionObserver((entries) => {
                        if (entries[0].isIntersecting && !this.loadingMore) {
                            this.fetchProducts(nextUrl, true);
                        }
                    }, { rootMargin: '200px' });
                    
                    this.observer.observe(trigger);
                }
            },
            
            init() {
                this.bindInfiniteScroll();
            }
        }));
    });
</script>
@endpush
