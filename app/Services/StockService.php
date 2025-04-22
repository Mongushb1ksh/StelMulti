<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockOperation;

class StockService
{
    public function recordReceipt(Product $product, int $quantity, ?string $notes): void
    {
        // Увеличиваем количество товара
        $product->increaseQuantity($quantity);

        // Создаем запись о приходе
        StockOperation::create([
            'product_id' => $product->id,
            'operation_type' => 'receipt',
            'quantity' => $quantity,
            'notes' => $notes,
        ]);
    }

    public function recordConsumption(Product $product, int $quantity, ?string $notes): void
    {
        // Уменьшаем количество товара
        $product->decreaseQuantity($quantity);

        // Создаем запись о расходе
        StockOperation::create([
            'product_id' => $product->id,
            'operation_type' => 'consumption',
            'quantity' => $quantity,
            'notes' => $notes,
        ]);
    }

    public function recordTransfer(Product $fromProduct, Product $toProduct, int $quantity): void
    {
        // Уменьшаем количество у отправителя
        $fromProduct->decreaseQuantity($quantity);

        // Увеличиваем количество у получателя
        $toProduct->increaseQuantity($quantity);

        // Создаем запись о перемещении
        StockOperation::create([
            'product_id' => $fromProduct->id,
            'operation_type' => 'transfer',
            'quantity' => $quantity,
            'notes' => "Перемещено на {$toProduct->name}",
        ]);
    }
}