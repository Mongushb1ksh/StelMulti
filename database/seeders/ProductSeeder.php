<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Стальная балка',
            'description' => 'Прочная стальная балка для строительства.',
            'price' => 5000.00,
            'quantity' => 100,
            'image' => 'beam.jpg',
        ]);

        Product::create([
            'name' => 'Металлический лист',
            'description' => 'Лист из нержавеющей стали.',
            'price' => 3000.00,
            'quantity' => 200,
            'image' => 'sheet.jpg',
        ]);

        Product::create([
            'name' => 'Труба стальная',
            'description' => 'Труба для промышленного использования.',
            'price' => 2000.00,
            'quantity' => 150,
            'image' => 'pipe.jpg',
        ]);
    }
}
