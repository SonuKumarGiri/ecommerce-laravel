@extends('admin.layouts.app')

@section('title', 'Update Order')

@section('content')
<div class="max-w-2xl">
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800">Update Order #{{ $order->order_number }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-indigo-600 transition-colors font-medium flex items-center gap-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to List
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('admin.orders.update', $order->id) }}" method="POST" class="p-8">
            @csrf
            @method('PUT')
            
            <div class="mb-6">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Order Status</label>
                <select name="status" id="status" required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                    <option value="placed" {{ old('status', $order->status) == 'placed' ? 'selected' : '' }}>Placed</option>
                    <option value="processing" {{ old('status', $order->status) == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ old('status', $order->status) == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ old('status', $order->status) == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ old('status', $order->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="mb-8">
                <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" id="payment_status" required
                    class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring focus:ring-indigo-200 transition-shadow shadow-sm">
                    <option value="pending" {{ old('payment_status', $order->payment_status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ old('payment_status', $order->payment_status) == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ old('payment_status', $order->payment_status) == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
                @error('payment_status')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center justify-end border-t border-gray-100 pt-6 mt-6">
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg font-medium shadow-md shadow-indigo-500/20 transition-all flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
