<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['product', 'manager'])
            ->latest()
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        $products = Product::all();
        $managers = User::where('role_id', 4)->get(); // Менеджеры по продажам
        
        return view('orders.create', compact('products', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'manager_id' => 'required|exists:users,id',
        ]);

        $validated['status'] = 'new';
        
        Order::create($validated);
        
        return redirect()->route('orders.index')
            ->with('success', 'Заказ успешно создан');
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        $products = Product::all();
        $managers = User::where('role_id', 4)->get();
        $statuses = ['new', 'processing', 'completed', 'cancelled'];
        
        return view('orders.edit', compact('order', 'products', 'managers', 'statuses'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'manager_id' => 'required|exists:users,id',
            'status' => 'required|in:new,processing,completed,cancelled',
        ]);

        $order->update($validated);
        
        return redirect()->route('orders.index')
            ->with('success', 'Заказ успешно обновлен');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        
        return redirect()->route('orders.index')
            ->with('success', 'Заказ успешно удален');
    }
}