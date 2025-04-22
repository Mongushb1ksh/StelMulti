<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $product_id
 * @property string $operation_type
 * @property int $quantity
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation whereOperationType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation whereQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|StockOperation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
