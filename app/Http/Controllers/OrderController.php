<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Auth::user()->role->name === 'Client'
            ? Order::getClientOrders()
            : Order::with('user', 'items')
                ->filter($request->only(['status', 'user_id']))
                ->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
            'users' => User::all()
        ]);
    }

    public function create()
    {
        return view('orders.create', [
            'users' => Auth::user()->role->name === 'Admin' ? User::all() : null
        ]);
    }

    public function store(Request $request)
    {
        try {
            Order::createOrder($request->all());
            return redirect()->route('orders.index')->with('success', 'Заказ успешно создан.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка при создании заказа: ' . $e->getMessage()]);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        try {
            $order->updateStatus($request->status);
            return redirect()->route('orders.index')->with('success', 'Статус заказа обновлен.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Ошибка обновления статуса']);
        }
    }

    public function show(Order $order)
    {
        return view('orders.show', ['order' => $order->load('user', 'items')]);
    }
}