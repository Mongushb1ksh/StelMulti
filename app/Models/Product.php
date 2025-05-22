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

    // Связь с операциями склада
    public function stockOperations()
    {
        return $this->hasMany(StockOperation::class);
    }

    // Увеличение количества товара (приход)
    public function recordReceipt($quantity, $notes = null)
    {
        $this->increment('quantity', $quantity);
        $this->logStockOperation('receipt', $quantity, $notes);
    }

    // Уменьшение количества товара (расход)
    public function recordConsumption($quantity, $notes = null)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('Недостаточно товара на складе.');
        }
        $this->decrement('quantity', $quantity);
        $this->logStockOperation('consumption', $quantity, $notes);
    }

    // Перемещение товара между продуктами
    public function recordTransfer(Product $toProduct, $quantity)
    {
        if ($this->quantity < $quantity) {
            throw new \Exception('Недостаточно товара на складе.');
        }

        // Уменьшаем количество у текущего продукта
        $this->decrement('quantity', $quantity);
        $this->logStockOperation('transfer_out', $quantity, "Перемещено к {$toProduct->name}");

        // Увеличиваем количество у целевого продукта
        $toProduct->increment('quantity', $quantity);
        $toProduct->logStockOperation('transfer_in', $quantity, "Перемещено от {$this->name}");
    }

    // Логирование операции
    protected function logStockOperation($type, $quantity, $notes = null)
    {
        $this->stockOperations()->create([
            'operation_type' => $type,
            'quantity' => $quantity,
            'notes' => $notes,
        ]);
    }

    // Проверка доступности товара для заказа
    public function isAvailableForOrder($quantity)
    {
        return $this->quantity >= $quantity;
    }
}