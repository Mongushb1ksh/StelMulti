<?php

namespace App\Http\Controllers;

use App\Models\ProductionTask;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $tasks = ProductionTask::with(['order', 'order.product'])
            ->latest()
            ->paginate(10);
            
        return view('production.index', compact('tasks'));
    }

    public function create()
    {
        $orders = Order::where('status', 'processing')->get();
        
        return view('production.create', compact('orders'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'start_date' => 'required|date',
            'comments' => 'nullable|string',
        ]);

        $validated['status'] = 'queued';
        
        ProductionTask::create($validated);
        
        return redirect()->route('production.index')
            ->with('success', 'Производственная задача создана');
    }

    public function show(ProductionTask $task)
    {
        return view('production.show', compact('task'));
    }

    public function edit(ProductionTask $task)
    {
        $orders = Order::where('status', 'processing')->get();
        $statuses = ['queued', 'in_progress', 'completed'];
        
        return view('production.edit', compact('task', 'orders', 'statuses'));
    }

    public function update(Request $request, ProductionTask $task)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|in:queued,in_progress,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'quality_check' => 'nullable|string',
        ]);

        $task->update($validated);
        
        return redirect()->route('production.index')
            ->with('success', 'Задача обновлена');
    }

    public function complete(ProductionTask $task)
    {
        $task->update([
            'status' => 'completed',
            'end_date' => now(),
        ]);
        
        return redirect()->route('production.index')
            ->with('success', 'Задача завершена');
    }

    public function destroy(ProductionTask $task)
    {
        $task->delete();
        
        return redirect()->route('production.index')
            ->with('success', 'Задача удалена');
    }
}