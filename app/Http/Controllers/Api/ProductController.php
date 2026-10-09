<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'min_price' => 'nullable|numeric|min:0',
                'max_price' => 'nullable|numeric|min:0',
                'category' => 'nullable|string|exists:categories,slug',
                'sort' => 'nullable|string|in:newest,price_low,price_high,name_asc,name_desc'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 400);
            }

            $query = Product::with('category')->where('status', 'active');

            if ($request->has('category')) {
                $query->whereHas('category', function($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($request->has('min_price')) {
                $query->where('price', '>=', $request->min_price);
            }

            if ($request->has('max_price')) {
                $query->where('price', '<=', $request->max_price);
            }

            $sort = $request->get('sort', 'newest');
            switch ($sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name_asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name_desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }

            $products = $query->paginate(12);

            Log::info('Products retrieved successfully via API', ['count' => $products->count()]);

            return ProductResource::collection($products)->additional([
                'status' => true,
                'message' => 'Products retrieved successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('API Error in ProductController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving products.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getProductDetails(string $id)
    {
        try {
            $product = Product::with('category')->where('status', 'active')->find($id);

            if (!$product) {
                return response()->json([
                    'status' => false,
                    'message' => 'Product not found or inactive.'
                ], 400);
            }
            
            Log::info('Product retrieved successfully via API', ['product_id' => $id]);

            return response()->json([
                'status' => true,
                'message' => 'Product retrieved successfully',
                'data' => new ProductResource($product)
            ], 200);

        } catch (\Throwable $e) {
            Log::error('API Error in ProductController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'product_id' => $id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving the product.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
