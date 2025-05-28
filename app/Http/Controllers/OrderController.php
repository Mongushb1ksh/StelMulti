<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->only(['status', 'client_name']);
        $query = Order::with(['product', 'manager'])->latest();

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['client_name'])) {
            $query->where('client_name', 'like', '%' . $filters['client_name'] . '%');
        }
        $orders = $query->paginate(10)->appends($filters);
        return view('orders.index', compact('orders', 'filters'));
    }

    public function create()
    {
        $products = Product::all();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        try {
            Order::createNewOrder($request->all());
            return redirect()->route('orders.index')
                ->with('success', 'Заказ успешно создан');
        } catch (\Exception $e){
            return redirect()->back()
                ->withInput()
                ->withErrors(['error'=> $e->getMessage()]);
        }
    }

    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        if (!$order->canBeEdited()) {
            return redirect()->back()->with('error', 'Завершенные или отмененные заказы нельзя редактировать');
        }
        $products = Product::all();
        return view('orders.edit', compact('order', 'products'));
    }

    public function update(Request $request, Order $order)
    {
        try {
            if (!$order->canBeEdited()) {
                return redirect()->back()->with('error', 'Завершенные или отмененные заказы нельзя редактировать');
            }

            Order::updateOrder($request->all(), $order->id);
    
            return redirect()->route('orders.index')
                ->with('success', 'Заказ успешно обновлен');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Order $order)
    {
        try {
            Order::deleteOrderById($order->id);

            return redirect()->route('orders.index')
                ->with('success', 'Заказ успешно удален');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}