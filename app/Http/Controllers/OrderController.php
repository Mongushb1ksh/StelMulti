<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{





    public function index(Request $request)
    {

        if (Auth::user()->role->name === 'Client') {
            $orders = Order::where('user_id', Auth::id())->paginate(10);
            $users = User::all();
        } else {
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
        }
        return view('orders.index', compact('orders', 'users'));            
        
    }

    public function create()
    {
        $users = User::all(); 
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
            $totalPrice = 0;
            foreach ($validatedData['items'] as $item) {
                $totalPrice += $item['quantity'] * $item['price'];
            }

            $order = Order::create([
                'user_id' => $validatedData['user_id'] ?? Auth::id(),
                'status' => 'new',
                'total_price' => $totalPrice,
            ]);

            $itemsGrouped = collect($validatedData['items'])->groupBy('product_name')->map(function ($group) {
                return [
                    'product_name' => $group->first()['product_name'],
                    'quantity' => $group->sum('quantity'),
                    'price' => $group->first()['price'],
                ];
            })->values();

            foreach ($itemsGrouped as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);
            }

            return redirect()->route('orders.index')->with('success', 'Заказ успешно создан.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при создании заказа. Попробуйте еще раз.']);
        }
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
