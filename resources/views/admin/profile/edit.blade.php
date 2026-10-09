@extends('admin.layouts.app')

@section('title', 'Admin Profile')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Profile Settings</h2>
    </div>

    <div class="space-y-6">

        {{-- Personal Information --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Personal Information</h3>
                <p class="text-sm text-gray-500 mt-0.5">Update your name and email address.</p>
            </div>
            <form action="{{ route('profile.update') }}" method="POST" class="p-6">
                @csrf
                @method('patch')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                            placeholder="Your full name">
                        @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm"
                            placeholder="your@email.com">
                        @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="flex items-center justify-end border-t border-gray-100 pt-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-base font-semibold text-gray-800">Update Password</h3>
                <p class="text-sm text-gray-500 mt-0.5">Ensure your account uses a strong, secure password.</p>
            </div>
            <form action="{{ route('password.update') }}" method="POST" class="p-6">
                @csrf
                @method('put')
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 mb-2">Current Password <span class="text-red-500">*</span></label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="current_password" id="current_password" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        @if($errors->updatePassword->has('current_password'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('current_password') }}</p>
                        @endif
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">New Password <span class="text-red-500">*</span></label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="password" id="password" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        @if($errors->updatePassword->has('password'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('password') }}</p>
                        @endif
                    </div>
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password <span class="text-red-500">*</span></label>
                        <div class="relative" x-data="{ show: false }">
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10"
                                placeholder="••••••••">
                            <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                                <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                            </button>
                        </div>
                        @if($errors->updatePassword->has('password_confirmation'))
                            <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center justify-end border-t border-gray-100 pt-4">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
