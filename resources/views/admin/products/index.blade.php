@extends('admin.layouts.app')

@section('title', 'Products')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-slate-50/50">
        <h2 class="text-xl font-bold text-gray-800">Manage Products</h2>
        
        <div class="flex flex-col xl:flex-row items-center gap-4 w-full sm:w-auto">
            <!-- Filter Form -->
            <form action="{{ route('admin.products.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full xl:w-auto">
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}" class="w-full sm:w-auto rounded-lg border-gray-300 text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                <select name="category_id" class="select2 w-full sm:w-64 rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2" data-placeholder="All Categories">
                    <option value="">All Categories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>
                <select name="status" class="w-full sm:w-auto rounded-lg border-gray-300 text-sm focus:ring-indigo-500 focus:border-indigo-500 py-2">
                    <option value="">All Statuses</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                <button type="submit" class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">
                    Filter
                </button>
            </form>

            <a href="{{ route('admin.products.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Product
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Product</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Category</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Price</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Stock</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Status</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($products as $product)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4">
                        <div class="font-semibold text-gray-800">{{ $product->name }}</div>
                        <div class="text-xs text-gray-400 mt-1">#{{ $product->slug }}</div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4 font-medium text-gray-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($product->price, 2) }}</td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $product->stock > 0 ? 'bg-blue-100 text-blue-800' : 'bg-red-100 text-red-800' }}">
                            {{ $product->stock }} in stock
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.products.toggle-status', $product->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $product->status === 'active' ? 'bg-indigo-600' : 'bg-gray-200' }}" role="switch" aria-checked="{{ $product->status === 'active' ? 'true' : 'false' }}">
                                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $product->status === 'active' ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                        <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this product?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                        <p class="text-lg font-medium text-gray-900">No products found</p>
                        <p class="mt-1 text-gray-500">Get started by creating a new product.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $products->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
