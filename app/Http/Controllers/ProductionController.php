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
    protected $productionService;

    public function __construct(ProductionService $productionService)
    {
        $this->productionService = $productionService;
    }

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

        try {
            $this->productionService->createTask($validatedData);
            return redirect()->route('production.index')->with('success', 'Задание успешно создано.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при создании задания. Попробуйте снова.']);
        }
    }

    public function updateStatus(Request $request, ProductionTask $task)
    {
        $request->validate([
            'status' => 'required|in:queued,in_progress,completed',
        ]);

        try {
            $this->productionService->updateTaskStatus($task, $request->status);
            return redirect()->route('production.index')->with('success', 'Статус задания обновлен.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при обновлении статуса.']);
        }
    }

    public function qualityCheck(Request $request, ProductionTask $task)
    {
        $request->validate([
            'quality_notes' => 'nullable|string',
        ]);

        try {
            $this->productionService->performQualityCheck($task, $request->quality_notes);
            return redirect()->route('production.index')->with('success', 'Качество проверено.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function reports()
    {
        $tasks = ProductionTask::with('order', 'workers.worker')->get();
        return view('production.reports', compact('tasks'));
    }
}