<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockOperation extends Model
{
    protected $fillable = [
        'product_id',
        'operation_type',
        'quantity',
        'notes',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
