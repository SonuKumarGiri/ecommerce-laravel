<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Requests\CategoryRequest;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Category::query();
            if ($request->has('search') && $request->search != '') {
                $query->where(function($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('slug', 'like', '%' . $request->search . '%');
                });
            }
            $categories = $query->latest()->paginate(15);
            return view('admin.categories.index', compact('categories'));
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to retrieve categories: ' . $th->getMessage());
        }
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(CategoryRequest $request)
    {
        try {
            Category::create($request->validated());

            return redirect()->route('admin.categories.index')->with('success', 'Category created successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to create category: ' . $th->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            return view('admin.categories.edit', compact('category'));
        } catch (\Throwable $th) {
            return redirect()->route('admin.categories.index')->with('error', 'Category not found.');
        }
    }

    public function update(CategoryRequest $request, $id)
    {
        try {
            $category = Category::findOrFail($id);

            $category->update($request->validated());

            return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to update category: ' . $th->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            if (Product::where('category_id', $id)->exists()) {
                return back()->with('error', 'Cannot delete category because it contains products.');
            }

            $category->delete();
            
            return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully');
        } catch (\Throwable $th) {
            return back()->with('error', 'Failed to delete category: ' . $th->getMessage());
        }
    }

    public function toggleStatus(Category $category)
    {
        $category->is_active = !$category->is_active;
        $category->save();
        return back()->with('success', 'Category status updated successfully.');
    }
}