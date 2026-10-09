@extends('admin.layouts.app')

@section('title', 'Edit Category')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Edit Category</h2>
        <a href="{{ route('admin.categories.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors font-medium flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Category Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $category->name) }}" required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            
            <div class="mb-8">
                <label class="flex items-center cursor-pointer group">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-5 h-5 cursor-pointer transition-all">
                    <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Active (Visible to customers)</span>
                </label>
            </div>

            <div class="flex items-center justify-end border-t border-gray-100 pt-6 mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Update Category
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
