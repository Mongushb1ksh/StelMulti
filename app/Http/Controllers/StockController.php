<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $products = Product::paginate(10);
        return view('stock.index', compact('products'));
    }

    public function receipt(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string', // Допускаем отсутствие поля notes
        ]);

        try {
            // Если notes не передано, используем null
            $notes = $validatedData['notes'] ?? null;
            $product->recordReceipt($validatedData['quantity'], $notes);
            return redirect()->route('stock.index')->with('success', 'Приход успешно зафиксирован.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function consumption(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
            'notes' => 'nullable|string', // Допускаем отсутствие поля notes
        ]);

        try {
            // Если notes не передано, используем null
            $notes = $validatedData['notes'] ?? null;
            $product->recordConsumption($validatedData['quantity'], $notes);
            return redirect()->route('stock.index')->with('success', 'Расход успешно зафиксирован.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function transfer(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
            'to_product_id' => 'required|exists:products,id',
        ]);

        try {
            $toProduct = Product::findOrFail($validatedData['to_product_id']);
            // Перемещение не требует notes, поэтому передаем null
            $product->recordTransfer($toProduct, $validatedData['quantity']);
            return redirect()->route('stock.index')->with('success', 'Перемещение успешно зафиксировано.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}