<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class HomeController extends Controller
{
    public function index()
    {
        try {
            $featuredProducts = Product::with('category')
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit(4)
                ->get();
                
            $categories = Category::where('is_active', true)
                ->orderBy('name')
                ->limit(6)
                ->get();
                
            return view('customer.home', compact('featuredProducts', 'categories'));
        } catch (Throwable $th) {
            Log::error('Home Index Error: ' . $th->getMessage());
            return view('customer.home', ['featuredProducts' => collect(), 'categories' => collect()])
                ->with('error', 'Unable to load products.');
        }
    }
}