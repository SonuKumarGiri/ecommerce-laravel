<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics.
     */
    public function index()
    {
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count(),
            'total_sales' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'status_counts' => [
                'placed' => Order::where('status', 'placed')->count(),
                'confirmed' => Order::where('status', 'confirmed')->count(),
                'processing' => Order::where('status', 'processing')->count(),
                'shipped' => Order::where('status', 'shipped')->count(),
                'delivered' => Order::where('status', 'delivered')->count(),
                'cancelled' => Order::where('status', 'cancelled')->count(),
            ],
            'pending_orders' => Order::whereIn('status', ['placed', 'confirmed', 'processing'])->count(),
            'successful_orders' => Order::where('status', 'delivered')->count(),
            'failed_orders' => Order::where('status', 'cancelled')->count(),
        ];

        // Sales Report for the last 7 days
        $salesData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $sales = Order::whereDate('created_at', $date)->where('payment_status', 'paid')->sum('total_amount');
            $salesData[] = [
                'date' => now()->subDays($i)->format('M d'),
                'sales' => $sales
            ];
        }

        $chartDates = collect($salesData)->pluck('date')->toJson();
        $chartSales = collect($salesData)->pluck('sales')->toJson();

        return view('admin.dashboard', compact('stats', 'chartDates', 'chartSales'));
    }
}
