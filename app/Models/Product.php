<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'quantity',
        'image',
    ];


    public function stockOperations(){
        return $this->hasMany(StockOperation::class);
    }

    public function increaseQuantity($quantity)
    {
        $this->increment('quantity', $quantity);
    }

    public function decreaseQuantity($quantity)
    {
        if($this->quantity < $quantity) {
            throw new \Exception('Недостаточно товара на складе.');
        }
        $this->decrement('quantity', $quantity);
    }
}

