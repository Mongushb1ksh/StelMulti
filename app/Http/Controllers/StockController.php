<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\StockOperation;

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
        ]);

        $product->increaseQuantity($validatedData['quantity']);

        StockOperation::create([
            'product_id' => $product->id,
            'operation_type' => 'receipt',
            'quantity' => $validatedData['quantity'],
            'notes' => $request->input('notes'),
        ]);


        return redirect()->route('stock.index')->with('success', 'Приход успешно зафиксирован.');
     
    }

    public function consumption(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            
            $product->decreaseQuantity($validatedData['quantity']);

            StockOperation::create([
                'product_id' => $product->id,
                'operation_type' => 'consumption',
                'quantity' => $validatedData['quantity'],
                'notes' => $request->input('notes'),
            ]);

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
            $product->decreaseQuantity($validatedData['quantity']);

            $toProduct = Product::find($validatedData['to_product_id']);
            $toProduct->increaseQuantity($validatedData['quantity']);

            StockOperation::create([
                'product_id' => $product->id,
                'operation_type' => 'transfer',
                'quantity' => $validatedData['quantity'],
                'notes' => "Перемещено на {$toProduct->name}",
            ]);

            return redirect()->route('stock.index')->with('success', 'Перемещение успешно зафиксировано.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }


}
