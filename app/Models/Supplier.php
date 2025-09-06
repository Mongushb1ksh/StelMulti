<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'contact_person',
        'tax_number',
        'status',
        'notes',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public static function validateData(array $data, $supplierId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:suppliers,email' . ($supplierId ? ",$supplierId" : ''),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'contact_person' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createSupplier(array $data): self
    {
        $validated = self::validateData($data);
        return self::create($validated);
    }

    public static function updateSupplier(array $data, int $id): self
    {
        $supplier = self::findOrFail($id);
        $validated = self::validateData($data, $id);
        $supplier->update($validated);
        return $supplier;
    }

    public static function filterSuppliers(array $filters)
    {
        $query = self::query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['email'])) {
            $query->where('email', 'like', '%' . $filters['email'] . '%');
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    public function getTotalOrdersAttribute()
    {
        return $this->purchaseOrders()->count();
    }

    public function getTotalSpentAttribute()
    {
        return $this->purchaseOrders()->where('status', 'received')->sum('total_amount');
    }

    public static function deleteSupplier(int $id): void
    {
        $supplier = self::findOrFail($id);
        $supplier->delete();
    }
}

