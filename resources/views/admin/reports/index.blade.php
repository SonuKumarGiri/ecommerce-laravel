@extends('admin.layouts.app')

@section('title', 'Reports & Analytics')

@section('content')
<div class="space-y-6" x-data="{ tab: 'sales' }">
    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Revenue</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($totalSales, 2) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-full bg-sky-50 text-sky-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Orders</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalOrders) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Users</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalUsers) }}</h3>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Products</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ number_format($totalProducts) }}</h3>
            </div>
        </div>
    </div>

    <!-- Tabs Navigation -->
    <div class="border-b border-slate-200">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <button @click="tab = 'sales'" :class="{'border-indigo-500 text-indigo-600': tab === 'sales', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': tab !== 'sales'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Sales Report
            </button>
            <button @click="tab = 'products'" :class="{'border-indigo-500 text-indigo-600': tab === 'products', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': tab !== 'products'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                Product Report
            </button>
            <button @click="tab = 'users'" :class="{'border-indigo-500 text-indigo-600': tab === 'users', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': tab !== 'users'}" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors">
                User Report
            </button>
        </nav>
    </div>

    <!-- Sales Report Tab -->
    <div x-show="tab === 'sales'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-cloak>
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800">Daily Sales (Last 30 Days)</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium border-b border-slate-100">Date</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100 text-right">Total Orders</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100 text-right">Revenue</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($salesData as $data)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ \Carbon\Carbon::parse($data->date)->format('M d, Y') }}</td>
                        <td class="px-6 py-4 font-medium text-slate-600 text-right">{{ $data->total_orders }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 text-right">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($data->total_revenue, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-slate-500">
                            No sales data available.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Product Report Tab -->
    <div x-show="tab === 'products'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-cloak>
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800">Low Stock Alert</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium border-b border-slate-100">Product Name</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100">Price</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100">Status</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100 text-right">Stock Left</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($lowStockProducts as $product)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-800">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-indigo-600 hover:underline">{{ $product->name }}</a>
                        </td>
                        <td class="px-6 py-4 text-slate-600">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($product->price, 2) }}</td>
                        <td class="px-6 py-4">
                            @if($product->status)
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">Active</span>
                            @else
                                <span class="px-2.5 py-1 rounded-full text-xs font-medium bg-rose-100 text-rose-800">Inactive</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-right {{ $product->stock <= 5 ? 'text-rose-600' : 'text-amber-600' }}">
                            {{ $product->stock }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            All products are sufficiently stocked.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- User Report Tab -->
    <div x-show="tab === 'users'" class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden" x-cloak>
        <div class="p-6 border-b border-slate-100 bg-slate-50/50">
            <h2 class="text-xl font-bold text-slate-800">Top Spenders</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-medium border-b border-slate-100">Customer Name</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100">Email Address</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100 text-right">Total Orders</th>
                        <th class="px-6 py-4 font-medium border-b border-slate-100 text-right">Total Spent</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($topUsers as $user)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-semibold text-slate-800">{{ $user->name }}</td>
                        <td class="px-6 py-4 text-slate-600">{{ $user->email }}</td>
                        <td class="px-6 py-4 font-medium text-slate-700 text-right">{{ $user->orders_count }}</td>
                        <td class="px-6 py-4 font-bold text-emerald-600 text-right">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($user->orders_sum_total_amount, 2) }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500">
                            No customers found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
