@extends('admin.layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="max-w-3xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Edit User</h2>
        <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors font-medium flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-6">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Change Password (Optional)</label>
                <div class="relative" x-data="{ show: false }">
                    <input :type="show ? 'text' : 'password'" name="password" id="password"
                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10"
                        placeholder="Leave blank to keep current password">
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 transition-colors focus:outline-none">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-2">Only fill this if you want to change the user's password.</p>
                @error('password')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>
            
            <div class="mb-8">
                <label class="flex items-center cursor-pointer group">
                    <input type="hidden" name="is_admin" value="0" {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                    <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 w-5 h-5 cursor-pointer transition-all"
                        {{ auth()->id() === $user->id ? 'disabled' : '' }}>
                    <span class="ml-3 text-sm font-medium text-gray-700 group-hover:text-gray-900 transition-colors">Grant Administrator Privileges</span>
                </label>
                @if(auth()->id() === $user->id)
                    <p class="text-xs text-orange-500 mt-2 ml-8">You cannot change your own role.</p>
                @endif
            </div>

            <div class="flex items-center justify-end border-t border-gray-100 pt-6 mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    Update User
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
