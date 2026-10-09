@extends('customer.layouts.app')

@section('title', 'Verify Email - ShopEase')

@section('content')
<div class="min-h-[calc(100vh-200px)] flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-slate-50">
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white shadow-xl rounded-2xl border border-slate-100">
        
        <div class="mb-6 text-center">
            <div class="w-16 h-16 bg-indigo-50 rounded-full flex items-center justify-center mx-auto mb-4 text-indigo-600">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
            <h2 class="text-2xl font-bold text-slate-900">Verify Your Email</h2>
            <p class="text-sm text-slate-500 mt-3 text-left">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
            </p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-100 text-sm font-medium text-green-800 text-center">
                A new verification link has been sent to the email address you provided during registration.
            </div>
        @endif

        <div class="flex flex-col space-y-4 mt-6">
            <form method="POST" action="{{ route('verification.send') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Resend Verification Email
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="w-full">
                @csrf
                <button type="submit" class="w-full flex justify-center py-2.5 px-4 border border-slate-300 rounded-lg shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
