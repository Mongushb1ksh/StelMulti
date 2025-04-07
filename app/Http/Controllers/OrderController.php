<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderController extends Controller
{





    public function index(Request $request)
    {
        $query = Order::with('user', 'items');

        if($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if($request->has('client')) {
            $query->where('user_id', $request->client);
        }
        
        if($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        $orders = $query->paginate(10);

        $users = User::all();

        return view('orders.index', compact('orders', 'users'));
    }

    public function create()
    {
        return view('orders.create');
    }

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
