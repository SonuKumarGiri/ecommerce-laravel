@extends('customer.layouts.app')

@section('title', 'Forgot Password - ShopEase')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex flex-col sm:justify-center items-center py-6 sm:py-8 bg-slate-50">
    <div class="w-full sm:max-w-md px-6 py-6 sm:px-8 sm:py-7 bg-white shadow-lg rounded-2xl border border-slate-100">
        
        <div class="mb-4 text-center">
            <h2 class="text-2xl font-bold text-slate-900">Forgot Password</h2>
            <p class="text-sm text-slate-500 mt-1">Enter your email and we'll send you a reset link.</p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-3" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                <input id="email" class="block w-full rounded-lg border-slate-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm text-sm" type="email" name="email" value="{{ old('email') }}" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-500 text-xs" />
            </div>

            <div class="mt-4">
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Email Password Reset Link
                </button>
            </div>
            
            <div class="mt-3 text-center">
                <a href="{{ route('login') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-500 transition-colors">Back to login</a>
            </div>
        </form>
    </div>
</div>
@endsection
