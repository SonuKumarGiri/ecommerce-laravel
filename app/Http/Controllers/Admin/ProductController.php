<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Support\Str;
use Throwable;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Product::with('category');
            
            if ($request->has('search') && $request->search != '') { 
                $query->where('name', 'like', '%' . $request->search . '%'); 
            } 
            if ($request->has('category_id') && $request->category_id != '') {
                $query->where('category_id', $request->category_id);
            }
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }
            
            $products = $query->latest()->paginate(15);
            $categories = Category::all();

            return view('admin.products.index', compact('products', 'categories'));
        } catch (Throwable $th) {
            return back()->with('error', 'Failed to retrieve products: ' . $th->getMessage());
        }
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $validated = $request->validated();

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = Str::random(25) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $validated['image'] = 'uploads/products/' . $filename;
            }

            Product::create($validated);

            return redirect()->route('admin.products.index')->with('success', 'Product created successfully');
        } catch (Throwable $th) {
            return back()->with('error', 'Failed to create product: ' . $th->getMessage())->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            $categories = Category::where('is_active', true)->get();
            return view('admin.products.edit', compact('product', 'categories'));
        } catch (Throwable $th) {
            return redirect()->route('admin.products.index')->with('error', 'Product not found.');
        }
    }

    public function update(UpdateProductRequest $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            
            $validated = $request->validated();

            if ($request->has('remove_image') && $request->remove_image == '1') {
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                $validated['image'] = null;
            }

            if ($request->hasFile('image')) {
                if ($product->image && file_exists(public_path($product->image))) {
                    unlink(public_path($product->image));
                }
                $file = $request->file('image');
                $filename = Str::random(25) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/products'), $filename);
                $validated['image'] = 'uploads/products/' . $filename;
            }

            $product->update($validated);

            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully');
        } catch (Throwable $th) {
            return back()->with('error', 'Failed to update product: ' . $th->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            if (OrderItem::where('product_id', $id)->exists()) {
                return back()->with('error', 'Cannot delete product because it is linked to existing orders. Please mark it as inactive instead.');
            }
            
            if ($product->image && file_exists(public_path($product->image))) {
                unlink(public_path($product->image));
            }
            $product->delete();
            
            return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully');
        } catch (Throwable $th) {
            return back()->with('error', 'Failed to delete product: ' . $th->getMessage());
        }
    }

    public function toggleStatus(Product $product)
    {
        $product->status = $product->status === 'active' ? 'inactive' : 'active';
        $product->save();
        return back()->with('success', 'Product status updated successfully.');
    }
}