<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    public function createOrder(array $data): Order
    {
        // Вычисляем общую стоимость заказа
        $totalPrice = collect($data['items'])->reduce(function ($carry, $item) {
            return $carry + ($item['quantity'] * $item['price']);
        }, 0);

        // Создаем заказ
        $order = Order::create([
            'user_id' => $data['user_id'] ?? Auth::id(),
            'status' => 'new',
            'total_price' => $totalPrice,
        ]);

        // Группируем товары по названию и создаем записи в таблице `order_items`
        $itemsGrouped = collect($data['items'])->groupBy('product_name')->map(function ($group) {
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

        return $order;
    }

    public function filterOrders(Request $request)
    {
        $query = Order::with('user', 'items');

        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        if ($request->has('client') && $request->client !== '') {
            $query->where('user_id', $request->client);
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('created_at', [$request->from_date, $request->to_date]);
        }

        return $query->paginate(10);
    }
}