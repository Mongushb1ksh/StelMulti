<?php

namespace App\Http\Controllers;

use App\Services\ProductionService;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\ProductionTask;
use Illuminate\Http\Request;

class ProductionController extends Controller
{
    public function index()
    {
        $tasks = ProductionTask::with('order', 'materials.material')->paginate(10);
        \Log::info('Loaded tasks:', $tasks->toArray()); // Логирование загруженных задач
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
        try {
            // Логирование входных данных
            \Log::info('Store request data:', $request->all());
    
            // Валидация данных
            $validatedData = $request->validate([
                'order_id' => 'required|exists:orders,id',
                'materials' => 'array',
                'materials.*.id' => 'nullable|exists:products,id',
                'materials.*.quantity_required' => 'nullable|integer|min:1',
            ]);
    
            // Фильтрация материалов: исключаем пустые или незаполненные поля
            $filteredMaterials = array_filter($validatedData['materials'], function ($material) {
                return !empty($material['id']) && !empty($material['quantity_required']);
            });
    
            // Создание задачи с валидированными данными
            $task = ProductionTask::createNewTask([
                'order_id' => $validatedData['order_id'],
                'materials' => $filteredMaterials,
            ]);
    
            // Редирект с сообщением об успехе
            return redirect()->route('production.index')->with('success', 'Задание успешно создано.');
        } catch (\Exception $e) {
            // Логирование ошибок
            \Log::error('Error creating task:', ['message' => $e->getMessage()]);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
    public function updateStatus(Request $request, ProductionTask $task)
    {
        try {
            $task->updateTaskStatus($request->input('status'));
            return redirect()->route('production.index')->with('success', 'Статус задания обновлен.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function qualityCheck(Request $request, ProductionTask $task)
    {
        try {
            $task->performQualityCheck($request->input('quality_notes'));
            return redirect()->route('production.index')->with('success', 'Качество проверено.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reports()
    {
        $tasks = ProductionTask::with('order')->get();
        return view('production.reports', compact('tasks'));
    }
}