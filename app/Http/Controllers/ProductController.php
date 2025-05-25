<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        try {
            Product::createProduct($request->all());
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
            Product::updateProduct($request->all(), $product->id);
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