@extends('customer.layouts.app')

@section('title', 'Login - ShopEase')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex flex-col sm:justify-center items-center py-8 bg-slate-50">
    <div class="w-full sm:max-w-md px-6 py-8 bg-white shadow-lg rounded-2xl border border-slate-100">
        
        <div class="mb-5 text-center">
            <h2 class="text-2xl font-bold text-slate-900">Welcome Back</h2>
            <p class="text-sm text-slate-500 mt-1">Please sign in to your account</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input id="email" class="block w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
            </div>

            <!-- Password -->
            <div class="mt-4">
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1">Password</label>
                <div class="relative" x-data="{ show: false }">
                    <input id="password" class="block w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm pr-10 text-sm"
                                    x-bind:type="show ? 'text' : 'password'"
                                    name="password"
                                    required autocomplete="current-password" />
                    <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-indigo-600 focus:outline-none transition-colors">
                        <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-500 text-xs" />
            </div>

            <!-- Remember Me & Forgot Password -->
            <div class="flex items-center justify-between pt-1 mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                    <span class="ms-2 text-sm text-slate-600">Remember me</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors" href="{{ route('password.request') }}">
                        Forgot password?
                    </a>
                @endif
            </div>

            <div class="pt-2 mt-4">
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Sign In
                </button>
            </div>
            
            <div class="pt-2 text-center">
                <p class="text-sm text-slate-600">
                    Don't have an account? 
                    <a href="{{ route('register') }}" class="font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Sign up</a>
                </p>
            </div>
        </form>
    </div>
</div>
@endsection
