<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // General Stats
        $totalSales = Order::where('payment_status', 'paid')->sum('total_amount');
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $totalProducts = Product::count();

        // 1. Sales Report (Last 30 Days)
        $salesData = Order::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_orders'),
            DB::raw('SUM(total_amount) as total_revenue')
        )
        ->where('created_at', '>=', now()->subDays(30))
        ->where('payment_status', 'paid')
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();

        // 2. Product Report (Stock alerts)
        $lowStockProducts = Product::where('stock', '<=', 10)
            ->orderBy('stock', 'asc')
            ->get();

        // 3. User Report (Top Spenders)
        $topUsers = User::withCount('orders')
            ->withSum(['orders' => function($query) {
                $query->where('payment_status', 'paid');
            }], 'total_amount')
            ->where('is_admin', false)
            ->orderByDesc('orders_sum_total_amount')
            ->take(10)
            ->get();

        return view('admin.reports.index', compact(
            'totalSales',
            'totalOrders',
            'totalUsers',
            'totalProducts',
            'salesData',
            'lowStockProducts',
            'topUsers'
        ));
    }
}