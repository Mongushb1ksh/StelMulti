<?php

namespace App\Http\Controllers;

use App\Services\StockService;
use App\Models\Product;
use Illuminate\Http\Request;

class StockController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

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

        try {
            $this->stockService->recordReceipt(
                $product,
                $validatedData['quantity'],
                $request->input('notes')
            );

            return redirect()->route('stock.index')->with('success', 'Приход успешно зафиксирован.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при фиксации прихода.']);
        }
    }

    public function consumption(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        try {
            $this->stockService->recordConsumption(
                $product,
                $validatedData['quantity'],
                $request->input('notes')
            );

            return redirect()->route('stock.index')->with('success', 'Расход успешно зафиксирован.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при фиксации расхода.']);
        }
    }

    public function transfer(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'quantity' => 'required|integer|min:1',
            'to_product_id' => 'required|exists:products,id',
        ]);

        try {
            $toProduct = Product::find($validatedData['to_product_id']);

            $this->stockService->recordTransfer(
                $product,
                $toProduct,
                $validatedData['quantity']
            );

            return redirect()->route('stock.index')->with('success', 'Перемещение успешно зафиксировано.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при фиксации перемещения.']);
        }
    }
}