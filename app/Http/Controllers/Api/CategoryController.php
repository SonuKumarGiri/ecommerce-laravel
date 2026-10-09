<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function getCategories(Request $request)
    {
        try {
            $query = Category::where('is_active', true);
            
            if ($request->boolean('with_products')) {
                $query->with(['products' => function($q) {
                    $q->where('status', 'active');
                }]);
            }
            
            if ($request->boolean('with_counts')) {
                $query->withCount(['products' => function($q) {
                    $q->where('status', 'active');
                }]);
            }

            $categories = $query->get();

            Log::info('Categories retrieved successfully via API', ['count' => $categories->count()]);

            return CategoryResource::collection($categories)->additional([
                'status' => true,
                'message' => 'Categories retrieved successfully'
            ]);

        } catch (\Throwable $e) {
            Log::error('API Error in CategoryController@index: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving categories.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getCategoryDetails(string $id)
    {
        try {
            $category = Category::where('is_active', true)
                ->with(['products' => function($q) {
                    $q->where('status', 'active');
                }])
                ->find($id);

            if (!$category) {
                return response()->json([
                    'status' => false,
                    'message' => 'Category not found or inactive.'
                ], 400);
            }
                
            Log::info('Category retrieved successfully via API', ['category_id' => $id]);
                
            return response()->json([
                'status' => true,
                'message' => 'Category retrieved successfully',
                'data' => new CategoryResource($category)
            ], 200);

        } catch (\Throwable $e) {
            Log::error('API Error in CategoryController@show: ' . $e->getMessage(), [
                'exception' => $e,
                'category_id' => $id
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An error occurred while retrieving the category.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
