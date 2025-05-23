<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::with('category')
            ->orderBy('quantity')
            ->paginate(10);
            
        return view('stock.index', compact('products'));
    }

    public function materials()
    {
        $materials = Material::orderBy('quantity')
            ->paginate(10);
            
        return view('stock.materials', compact('materials'));
    }

    public function addMaterial(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        Material::create($validated);
        
        return redirect()->route('stock.materials')
            ->with('success', 'Материал добавлен');
    }

    public function adjustStock(Request $request, Material $material)
    {
        $validated = $request->validate([
            'action' => 'required|in:add,remove',
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        if ($validated['action'] === 'add') {
            $material->increment('quantity', $validated['quantity']);
        } else {
            $material->decrement('quantity', $validated['quantity']);
        }
        
        // Логирование операции...
        
        return redirect()->route('stock.materials')
            ->with('success', 'Количество материала обновлено');
    }
}