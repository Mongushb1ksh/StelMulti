<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['category_id', 'name']);
        $query = Product::with('category')->latest();

        if (!empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
    
        $products = $query->paginate(10)->appends($filters);
        $categories = Category::all();
        return view('products.index', compact('products', 'categories', 'filters'));;
    }
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            
            Product::createProduct($data);
            return redirect()->route('products.index')->with('success', 'Товар успешно добавлен');
        } catch (\Exception $e){
            return redirect()->back()
                ->withErrors(['error'=> $e->getMessage()]);
        }
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        try {
            $data = $request->all();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }
            if ($request->has('remove_image')) {
                $data['image'] = null;
            }
            
            Product::updateProduct($data, $product->id);
            return redirect()->route('products.index')->with('success', 'Товар успешно обновлен');
        } catch (\Exception $e){
            return redirect()->back()
                ->withErrors(['error'=> $e->getMessage()]);
        }
    }

    public function destroy(Product $product)
    {

        try {
            Product::deleteProduct($product->id);

            return redirect()->route('orders.index')
                ->with('success', 'Товар успешно удален');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    
}