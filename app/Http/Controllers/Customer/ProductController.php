<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category')->where('status', 'active');

            // Search
            if ($request->filled('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            // Category Filter
            if ($request->filled('categories')) {
                $categories = is_array($request->categories) ? $request->categories : (array)$request->categories;
                $query->whereHas('category', function ($q) use ($categories) {
                    $q->whereIn('slug', $categories);
                });
            }

            // Price Filter
            if ($request->filled('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }
            if ($request->filled('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            // Sorting
            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'price_low_high':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high_low':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }

            $products = $query->paginate(12)->withQueryString();

            if ($request->ajax() || $request->wantsJson()) {
                return view('customer.products.partials.list', compact('products'))->render();
            }

            $categories = Category::where('is_active', true)
                ->whereHas('products', function($query) {
                    $query->where('status', 'active');
                })
                ->withCount(['products' => function($query) {
                    $query->where('status', 'active');
                }])
                ->orderBy('name')
                ->get();

            $maxPriceDB = Product::where('status', 'active')->max('price');
            $maxProductPrice = $maxPriceDB ? ceil($maxPriceDB / 100) * 100 : 5000;

            return view('customer.products.index', compact('products', 'categories', 'maxProductPrice'));
            
        } catch (Throwable $th) {
            Log::error('Product Index Error: ' . $th->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['error' => 'Failed to load products.'], 500);
            }
            
            return back()->with('error', 'Unable to load products.');
        }
    }

    public function show($slug)
    {
        try {
            $product = Product::with('category')->where('slug', $slug)->where('status', 'active')->firstOrFail();
            
            $relatedProducts = Product::where('category_id', $product->category_id)
                ->where('id', '!=', $product->id)
                ->where('status', 'active')
                ->inRandomOrder()
                ->limit(4)
                ->get();

            return view('customer.products.show', compact('product', 'relatedProducts'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('products.index')->with('error', 'Product not found.');
        } catch (Throwable $th) {
            Log::error('Product Show Error: ' . $th->getMessage());
            return redirect()->route('products.index')->with('error', 'Unable to load product details.');
        }
    }
}