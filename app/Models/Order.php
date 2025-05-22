<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Request;
use App\Models\User;



class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'notes',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public static function validateOrderData(array $data): array
    {
        return Validator::make($data, [
            'user_id' => 'nullable|exists:users,id',
            'items' => 'required|array',
            'items.*.product_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'status' => ['sometimes', Rule::in(['new', 'processing', 'production', 'completed', 'shipped'])],
        ])->validate();
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
        return $query
            ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']))
            ->when(isset($filters['user_id']), fn($q) => $q->where('user_id', $filters['user_id']));
    }

    public static function getClientOrders()
    {
        return self::where('user_id', Auth::id())->paginate(10);
    }

    public static function createOrder(array $data): self
    {
        $validated = self::validateOrderData($data);

        $order = self::create([
            'user_id' => $validated['user_id'] ?? Auth::id(),
            'status' => 'new',
            'total_price' => self::calculateTotal($validated['items'])
        ]);

        $order->items()->createMany($validated['items']);

        return $order;
    }

    public function updateStatus(string $status): void
    {
        $this->update(['status' => $status]);
    }

    protected static function calculateTotal(array $items): float
    {
        return array_reduce($items, fn($sum, $item) => $sum + ($item['quantity'] * $item['price']), 0);
    }
}