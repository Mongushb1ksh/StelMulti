<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;


class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'category_id',
        'unit_price',
        'image',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public static function validateData(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createProduct(array $data): self
    {
        $validated = self::validateData($data, true);

        if (isset($validated['image'])) {
            $path = $validated['image']->store('products', 'public');
            $validated['image'] = $path;
        }

        return self::create($validated);
    }


    public static function updateProduct(array $data, int $id): self
    {
        $product = self::find($id);
        $validated = self::validateData($data, true);

        if (isset($validated['image'])) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            $path = $validated['image']->store('products', 'public');
            $validated['image'] = $path;
        }

        $product->update($validated);
        return $product;
    }
    public static function filterProducts(array $filters)
    {
        $query = self::query();
        if (isset($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        return $query->get();
    }

    public function isAvailable(int $quantity = 1): bool
    {
        return $this->quantity >= $quantity;
    }

    public function decreaseQuantity(int $quantity): bool
    {
        $this->quantity -= $quantity;
        return $this->save();
    }

    public function increaseQuantity(int $quantity): bool
    {
        $this->quantity += $quantity;
        return $this->save();
    }
    public static function deleteOrderById(int $id): void
    {
            $order = self::findOrFail($id);
            $order->delete();
    }
    public static function deleteProduct(int $id): void
    {
        $product = self::find($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete();
        
    }
}