@extends('admin.layouts.app')

@section('title', 'Overview')

@section('content')
<div class="mb-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h2 class="text-3xl font-bold text-gray-800 tracking-tight">Welcome back!</h2>
        <p class="text-gray-500 mt-1 text-sm">Here is what's happening with your store today.</p>
    </div>
    <div class="w-full sm:w-auto">
        <a href="{{ route('admin.reports.index') }}" class="w-full sm:w-auto justify-center bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-lg font-medium shadow-lg shadow-indigo-500/30 transition-all duration-200 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Export Report
        </a>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Sales -->
    <div class="relative overflow-hidden bg-gradient-to-br from-indigo-500 to-purple-600 p-6 rounded-2xl shadow-lg shadow-indigo-500/30 text-white hover:-translate-y-1 transition-all duration-300">
        <div class="absolute right-0 top-0 opacity-10">
            <svg class="w-32 h-32 -mr-8 -mt-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <div class="relative z-10">
            <h3 class="text-indigo-100 text-sm font-medium uppercase tracking-wider mb-1">Total Sales</h3>
            <p class="text-4xl font-extrabold tracking-tight">{{ $siteSettings['currency_symbol'] ?? '₹' }}{{ number_format($stats['total_sales'] ?? 0, 2) }}</p>
            <p class="text-indigo-100 text-sm mt-3 flex items-center gap-1">
                <svg class="w-4 h-4 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                <span class="text-green-300 font-semibold">+12.5%</span> from last month
            </p>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Orders</h3>
            <div class="w-12 h-12 rounded-full bg-blue-50 text-blue-500 flex items-center justify-center group-hover:bg-blue-500 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_orders'] ?? 0 }}</p>
    </div>

    <!-- Total Products -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Products</h3>
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-500 flex items-center justify-center group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_products'] ?? 0 }}</p>
    </div>

    <!-- Total Users -->
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 hover:shadow-md hover:-translate-y-1 transition-all duration-300 group">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-gray-500 text-sm font-medium uppercase tracking-wider">Total Users</h3>
            <div class="w-12 h-12 rounded-full bg-orange-50 text-orange-500 flex items-center justify-center group-hover:bg-orange-500 group-hover:text-white transition-colors duration-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-gray-800">{{ $stats['total_users'] ?? 0 }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <!-- Order Status Breakdown -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
        <h3 class="text-lg font-bold text-gray-800 mb-6">Order Status</h3>
        <div class="space-y-4">
            <!-- Placed -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-slate-400"></div>
                    <span class="text-gray-600 font-medium">Placed</span>
                </div>
                <span class="font-bold text-gray-800">{{ $stats['status_counts']['placed'] ?? 0 }}</span>
            </div>
            <!-- Confirmed -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-blue-400"></div>
                    <span class="text-gray-600 font-medium">Confirmed</span>
                </div>
                <span class="font-bold text-gray-800">{{ $stats['status_counts']['confirmed'] ?? 0 }}</span>
            </div>
            <!-- Processing -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                    <span class="text-gray-600 font-medium">Processing</span>
                </div>
                <span class="font-bold text-gray-800">{{ $stats['status_counts']['processing'] ?? 0 }}</span>
            </div>
            <!-- Shipped -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-indigo-400"></div>
                    <span class="text-gray-600 font-medium">Shipped</span>
                </div>
                <span class="font-bold text-gray-800">{{ $stats['status_counts']['shipped'] ?? 0 }}</span>
            </div>
            <!-- Delivered (Successful) -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                    <span class="text-gray-600 font-medium">Delivered</span>
                </div>
                <span class="font-bold text-gray-800">{{ $stats['status_counts']['delivered'] ?? 0 }}</span>
            </div>
            <!-- Cancelled (Failed) -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-3 h-3 rounded-full bg-red-400"></div>
                    <span class="text-gray-600 font-medium">Cancelled</span>
                </div>
                <span class="font-bold text-gray-800">{{ $stats['status_counts']['cancelled'] ?? 0 }}</span>
            </div>
        </div>
    </div>

    <!-- Sales Chart -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-2 flex flex-col">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-bold text-gray-800">Revenue Overview</h3>
            <select class="border-gray-200 rounded-lg text-sm text-gray-500 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
                <option>This Year</option>
            </select>
        </div>
        <div class="flex-1 min-h-[250px] relative">
            <canvas id="salesChart"></canvas>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Gradient for chart area
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(79, 70, 229, 0.2)'); // Indigo 600 with opacity
        gradient.addColorStop(1, 'rgba(79, 70, 229, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! $chartDates !!},
                datasets: [{
                    label: 'Sales ({{ $siteSettings["currency_symbol"] ?? "₹" }})',
                    data: {!! $chartSales !!},
                    borderColor: '#4f46e5',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: '#4f46e5',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        padding: 12,
                        titleFont: { size: 13 },
                        bodyFont: { size: 14, weight: 'bold' },
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return '{{ $siteSettings["currency_symbol"] ?? "₹" }}' + context.parsed.y.toFixed(2);
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { color: '#64748b', font: { size: 12 } }
                    },
                    y: {
                        border: { display: false },
                        grid: {
                            color: '#f1f5f9',
                            drawTicks: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: { size: 12 },
                            padding: 10,
                            callback: function(value) {
                                return '{{ $siteSettings["currency_symbol"] ?? "₹" }}' + value;
                            }
                        },
                        beginAtZero: true
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
            }
        });
    });
</script>
@endpush
