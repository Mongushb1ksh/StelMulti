<?php

namespace App\Http\Controllers;

use App\Models\ProductionTask;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status']);
        $query = ProductionTask::with('order')->latest();
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        $productionTasks = $query->paginate(10)->appends($filters);
        return view('production.index', compact('productionTasks', 'filters'));
    }

    public function create()
    {
        $orders = Order::where('status', Order::STATUS_PENDING)
            ->with('product')
            ->get();
            
        return view('production.create', compact('orders'));
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $data['start_date'] = now();
            
            ProductionTask::createTask($data);

            $order = Order::find($data['order_id']);
            $order->update(['status' => Order::STATUS_PROCESSING]);

            return redirect()->route('production.index')
                ->with('success', 'Производственная задача успешно создана');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function show(ProductionTask $productionTask)
    {
        $productionTask->load('order.product');
        return view('production.show', compact('productionTask'));
    }

    public function edit(ProductionTask $productionTask)
    {
        $orders = Order::where('status', Order::STATUS_PROCESSING)
            ->with('product')
            ->get();
            
        return view('production.edit', compact('productionTask', 'orders'));
    }

    public function update(Request $request, ProductionTask $productionTask)
    {
        try {
            ProductionTask::updateTask($request->all(), $productionTask->id);
            
            return redirect()->route('production.show', $productionTask)
                ->with('success', 'Задача успешно обновлена');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function complete(ProductionTask $productionTask, Request $request)
    {
        try {
            $request->validate([
                'quality_check' => 'required|string'
            ]);

            $order = Order::find($request['order_id']);
            $order->update(['status' => Order::STATUS_COMPLETED]);
            $productionTask->completeTask($request->quality_check);
            
            return redirect()->route('production.show', $productionTask)
                ->with('success', 'Задача успешно завершена');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(ProductionTask $productionTask)
    {
        try {
            ProductionTask::deleteTask($productionTask->id);
            
            return redirect()->route('production.index')
                ->with('success', 'Задача успешно удалена');
        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}