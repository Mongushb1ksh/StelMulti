<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'company',
        'tax_number',
        'status',
        'notes',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public static function validateData(array $data, $customerId = null)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:customers,email' . ($customerId ? ",$customerId" : ''),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'company' => 'nullable|string|max:255',
            'tax_number' => 'nullable|string|max:50',
            'status' => 'required|in:active,inactive,prospect',
            'notes' => 'nullable|string',
        ];

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createCustomer(array $data): self
    {
        $validated = self::validateData($data);
        return self::create($validated);
    }

    public static function updateCustomer(array $data, int $id): self
    {
        $customer = self::findOrFail($id);
        $validated = self::validateData($data, $id);
        $customer->update($validated);
        return $customer;
    }

    public static function filterCustomers(array $filters)
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

        return $query->paginate(15);
    }

    public function getTotalOrdersAttribute()
    {
        return $this->orders()->count();
    }

    public function getTotalRevenueAttribute()
    {
        return $this->orders()->where('status', 'completed')->sum('total_amount');
    }

    public static function deleteCustomer(int $id): void
    {
        $customer = self::findOrFail($id);
        $customer->delete();
    }
}

