@extends('admin.layouts.app')

@section('title', 'Add Product')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Create New Product</h2>
        <a href="{{ route('admin.products.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors font-medium flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="p-8">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Product Name -->
                <div class="col-span-2 md:col-span-1">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Product Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="Enter product name">
                    @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Category -->
                <div class="col-span-2 md:col-span-1">
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Category <span class="text-red-500">*</span></label>
                    <select name="category_id" id="category_id" required data-placeholder="Select a category"
                        class="select2 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Price -->
                <div class="col-span-2 md:col-span-1">
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price ($) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" min="1" name="price" id="price" value="{{ old('price') }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="0.00">
                    @error('price')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Stock -->
                <div class="col-span-2 md:col-span-1">
                    <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">Available Stock <span class="text-red-500">*</span></label>
                    <input type="number" min="0" name="stock" id="stock" value="{{ old('stock', 0) }}" required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                    @error('stock')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Status -->
                <div class="col-span-2">
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" id="status" required
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active (Visible)</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive (Hidden)</option>
                    </select>
                    @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Product Image -->
                <div class="col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">Product Image</label>
                    <input type="file" name="image" id="image" class="dropify" data-height="120" accept="image/*">
                    @error('image')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>

                <!-- Description -->
                <div class="col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="description" rows="5"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                        placeholder="Write a compelling product description...">{{ old('description') }}</textarea>
                    @error('description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="flex items-center justify-end border-t border-gray-100 pt-6 mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Product
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
