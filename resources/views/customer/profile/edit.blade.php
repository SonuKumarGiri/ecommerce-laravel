@extends('customer.layouts.app')

@section('title', 'My Profile - ShopEase')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    <div class="mb-8">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900">Profile Settings</h2>
        <p class="text-slate-500 mt-2">Manage your account details and security settings.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Sidebar --}}
        <div class="col-span-1">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden sticky top-28">
                <div class="p-6 text-center border-b border-slate-100">
                    <div class="w-20 h-20 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 text-3xl font-bold mx-auto shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <h3 class="mt-4 font-bold text-slate-900 text-lg">{{ auth()->user()->name }}</h3>
                    <p class="text-sm text-slate-500">{{ auth()->user()->email }}</p>
                </div>
                <div class="p-2">
                    <a href="{{ route('orders.index') }}" class="flex items-center gap-3 px-4 py-3 text-slate-600 hover:bg-slate-50 hover:text-indigo-600 rounded-lg transition-colors font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        My Orders
                    </a>
                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-3 text-indigo-600 bg-indigo-50 rounded-lg transition-colors font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Profile Settings
                    </a>
                </div>
            </div>
        </div>

        {{-- Forms --}}
        <div class="col-span-1 md:col-span-2 space-y-6">

            {{-- Personal Information --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Personal Information</h3>
                    <p class="text-sm text-slate-500">Update your name and email address.</p>
                </div>
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">@csrf</form>
                <form method="POST" action="{{ route('profile.update') }}" class="p-6">
                    @csrf
                    @method('patch')
                    <div class="flex flex-col gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                            @error('name')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                            @error('email')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <p class="text-sm mt-2 text-gray-600">
                                    {{ __('Your email address is unverified.') }}
                                    <button form="send-verification" class="underline text-indigo-600 hover:text-indigo-800">Re-send verification email.</button>
                                </p>
                            @endif
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all">
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Update Password --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-900">Update Password</h3>
                    <p class="text-sm text-slate-500">Ensure your account uses a strong, secure password.</p>
                </div>
                <form method="POST" action="{{ route('password.update') }}" class="p-6">
                    @csrf
                    @method('put')
                    <div class="flex flex-col gap-4">
                        <div>
                            <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="current_password" id="current_password"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10">
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
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password" id="new_password"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10">
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
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <div class="relative" x-data="{ show: false }">
                                <input :type="show ? 'text' : 'password'" name="password_confirmation" id="password_confirmation"
                                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm pr-10">
                                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-indigo-600 focus:outline-none">
                                    <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                </button>
                            </div>
                            @if($errors->updatePassword->has('password_confirmation'))
                                <p class="mt-1 text-sm text-red-600">{{ $errors->updatePassword->first('password_confirmation') }}</p>
                            @endif
                        </div>
                        <div class="pt-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all">
                                Update Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
