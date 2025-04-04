<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Просмотр всех заказов
    public function index()
    {
        $orders = Order::with('user', 'items')->paginate(10);
        return view('orders.index', compact('orders'));
    }

    // Создание заказа
    public function create()
    {
        return view('orders.create');
    }

    // Сохранение заказа
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        $order = Order::create([
            'user_id' => $validatedData['user_id'],
            'status' => 'new',
        ]);

        foreach ($validatedData['items'] as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_name' => $item['product_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Заказ успешно создан.');
    }

    // Обновление статуса заказа
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:new,processing,production,completed,shipped',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')->with('success', 'Статус заказа обновлен.');
    }

    // Просмотр деталей заказа
    public function show(Order $order)
    {
        $order->load('user', 'items');
        return view('orders.show', compact('order'));
    }
}
