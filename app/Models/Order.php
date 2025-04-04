<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'status',
        'total_price',
        'notes',
    ];

    // Связь с пользователем (клиентом)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Связь с товарами в заказе
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}