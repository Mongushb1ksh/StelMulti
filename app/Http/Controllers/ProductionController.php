<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\ProductionTask;
use App\Models\Product;
use App\Models\User;
use App\Models\TaskMaterial;
use App\Models\StockOperation;
use App\Models\TaskWorker;
use App\Models\OrderItem;
use App\Models\Role;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $tasks = ProductionTask::with('order', 'materials.material', 'workers.worker')->paginate(10);
        return view('production.index', compact('tasks'));
    
    }

    public function create()
    {
        $orders = Order::where('status', 'new')->get();
        $materials = Product::where('type', 'material')->get();
        $workers = User::where('role_id', '5')->get();
        return view('production.create', compact('orders', 'materials', 'workers'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'materials' => 'required|array',
            'materials.*.id' => 'required|exists:products,id',
            'materials.*.quantity_required' => 'required|integer|min:1',
            'workers' => 'required|array',
            'workers.*' => 'exists:users,id',
        ]);

        // Создаем задание
        $task = ProductionTask::create([
            'order_id' => $validatedData['order_id'],
            'status' => 'queued',
        ]);

        // Добавляем материалы для задания
        foreach ($validatedData['materials'] as $material) {
            TaskMaterial::create([
                'task_id' => $task->id,
                'material_id' => $material['id'],
                'quantity_required' => $material['quantity_required'],
            ]);
        }

        // Назначаем сотрудников
        foreach ($validatedData['workers'] as $workerId) {
            TaskWorker::create([
                'task_id' => $task->id,
                'user_id' => $workerId,
            ]);
        }

        return redirect()->route('production.index')->with('success', 'Задание успешно создано.');
   
    }

    public function updateStatus(Request $request, ProductionTask $task)
    {
        $request->validate([
            'status' => 'required|in:queued,in_progress,completed',
        ]);

        // Обновляем статус
        $task->update(['status' => $request->status]);

        return redirect()->route('production.index')->with('success', 'Статус задания обновлен.');
  
    }

    public function qualityCheck(Request $request, ProductionTask $task)
    {
        $request->validate([
            'quality_notes' => 'nullable|string',
        ]);

        if ($task->status !== 'completed') {
            return redirect()->back()->withErrors(['error' => 'Задание еще не завершено.']);
        }

        // Логика проверки качества (например, ручная проверка или автоматическая)
        $task->update([
            'status' => 'quality_checked',
            'notes' => $request->quality_notes,
        ]);

        return redirect()->route('production.index')->with('success', 'Качество проверено.');
    
    }

    public function reports()
    {
        $tasks = ProductionTask::with('order', 'worker.worker')->get();
    
        return view('production.reports', compact('tasks'));
    }

}
