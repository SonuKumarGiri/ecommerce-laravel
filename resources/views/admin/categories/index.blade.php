@extends('admin.layouts.app')

@section('title', 'Categories')

@section('content')
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4 bg-slate-50/50">
        <h2 class="text-xl font-bold text-gray-800">Manage Categories</h2>
        <div class="flex flex-col xl:flex-row items-center gap-4 w-full sm:w-auto">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="flex flex-col sm:flex-row items-center gap-2 w-full xl:w-auto">
                <input type="text" name="search" placeholder="Search categories..." value="{{ request('search') }}" class="w-full sm:w-auto rounded-lg border-gray-300 text-sm py-2 focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 px-3 py-2 rounded-lg text-sm font-medium transition-colors">Search</button>
            </form>
            <a href="{{ route('admin.categories.create') }}" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all shadow-md shadow-indigo-500/20 flex items-center justify-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Category
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse whitespace-nowrap">
            <thead>
                <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                    <th class="px-6 py-4 font-medium border-b border-gray-100">ID</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Name</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Slug</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100">Status</th>
                    <th class="px-6 py-4 font-medium border-b border-gray-100 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100 text-sm">
                @forelse ($categories as $category)
                <tr class="hover:bg-slate-50 transition-colors group">
                    <td class="px-6 py-4 text-gray-500">#{{ $category->id }}</td>
                    <td class="px-6 py-4 font-semibold text-gray-800">{{ $category->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $category->slug }}</td>
                    <td class="px-6 py-4">
                        <form action="{{ route('admin.categories.toggle-status', $category->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none {{ $category->is_active ? 'bg-indigo-600' : 'bg-gray-200' }}" role="switch" aria-checked="{{ $category->is_active ? 'true' : 'false' }}">
                                <span aria-hidden="true" class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out {{ $category->is_active ? 'translate-x-5' : 'translate-x-0' }}"></span>
                            </button>
                        </form>
                    </td>
                    <td class="px-6 py-4 text-right space-x-3">
                        <a href="{{ route('admin.categories.edit', $category->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-100 hover:text-indigo-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this category?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                        <p class="text-lg font-medium text-gray-900">No categories found</p>
                        <p class="mt-1 text-gray-500">Get started by creating a new category.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($categories->hasPages())
    <div class="p-6 border-t border-gray-100">
        {{ $categories->withQueryString()->links() }}
    </div>
    @endif
</div>
@endsection
