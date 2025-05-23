<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\ProductionTask;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $data = [];
        
        if (Auth::check()) {
            $data = [
                'ordersCount' => Order::count(),
                'tasksCount' => ProductionTask::where('status', '!=', 'completed')->count(),
                'productsCount' => Product::count(),
                'recentTasks' => ProductionTask::with('order')
                    ->latest()
                    ->take(5)
                    ->get()
            ];
        }

        return view('home', $data);
    }
}