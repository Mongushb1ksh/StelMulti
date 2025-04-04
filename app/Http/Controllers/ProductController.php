<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Просмотр каталога
    public function index()
    {
        $products = Product::paginate(10);
        return view('catalog.index', compact('products'));
    }

    // Просмотр деталей продукта
    public function show(Product $product)
    {
        return view('catalog.show', compact('product'));
    }
}
