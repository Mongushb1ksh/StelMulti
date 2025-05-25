<?php

namespace App\Http\Controllers;

use App\Models\ProductionTask;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $tasks = ProductionTask::with('order')->latest()->paginate(10);
        return view('production.index', compact('tasks'));
    }

    public function create()
    {
        $orders = Order::where('status', Order::STATUS_PROCESSING)->get();
        return view('production.create', compact('orders'));
    }

    public function store(Request $request)
    {
        try {        
            $data = $request->all();
            $data['start_date'] = now();
            ProductionTask::createTask($request->all());
            return redirect()->route('products.index')->with('success', 'Производственная задача создана');;
        } catch (\Exception $e){
            return redirect()->back()
                ->withErrors(['error'=> $e->getMessage()]);
        }
    }

    public function show(ProductionTask $productionTask)
    {
        return view('production.show', compact('productionTask'));
    }

    public function edit(ProductionTask $productionTask)
    {
        $orders = Order::where('status', Order::STATUS_PROCESSING)->get();
        return view('production.edit', compact('productionTask', 'orders'));
    }

    public function update(Request $request, ProductionTask $productionTask)
    {
        try {
            ProductionTask::createTask($request->all(), $productionTask->id);
            return redirect()->route('products.index')->with('success', 'Задача обновлена');;;
        } catch (\Exception $e){
            return redirect()->back()
                ->withErrors(['error'=> $e->getMessage()]);
        }
    }

    public function complete(Request $request, ProductionTask $productionTask)
    {
        $validated = $request->validate(['quality_check' => 'required|string',]);
        $productionTask->completeTask($validated['quality_check']);
        return redirect()->route('production-tasks.show', $productionTask)->with('success', 'Производственная задача завершена');
    }

    public function destroy(ProductionTask $productionTask)
    {
        try {
            ProductionTask::deleteTask($productionTask->id);
            return redirect()->route('production.index')->with('success', 'Задача удалена');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}