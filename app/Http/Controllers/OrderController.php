<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {
        if (Auth::user()->role->name === 'Client') {
            $orders = Order::where('user_id', Auth::id())->paginate(10);
        } else {
            $orders = $this->orderService->filterOrders($request);
        }

        $users = User::all();
        return view('orders.index', compact('orders', 'users'));
    }

    public function create()
    {
        $users = Auth::user()->role->name === 'Admin' ? User::all() : null;
        return view('orders.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
        ]);

        try {
            $this->orderService->createOrder($validatedData);
            return redirect()->route('orders.index')->with('success', 'Заказ успешно создан.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при создании заказа. Попробуйте еще раз.']);
        }
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:new,processing,production,completed,shipped',
        ]);

        $order->update(['status' => $request->status]);

        return redirect()->route('orders.index')->with('success', 'Статус заказа обновлен.');
    }

    public function show(Order $order)
    {
        $order->load('user', 'items');
        return view('orders.show', compact('order'));
    }
}