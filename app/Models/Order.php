<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_name',
        'client_email',
        'product_id',
        'quantity',
        'status',
        'manager_id',
    ];

    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';


    public static $statuses = [
        self::STATUS_PENDING => 'В ожидании',
        self::STATUS_PROCESSING => 'В работе',
        self::STATUS_COMPLETED => 'Завершен',
        self::STATUS_CANCELLED => 'Отменен',
    ];


    private static function validateOrderData(array $data)
    {
        $validator = Validator::make($data, [
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);
        
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $validator->validated();
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function productionTask()
    {
        return $this->hasOne(ProductionTask::class);
    }

    public static function createNewOrder(array $data): self
    {
        $validated = self::validateOrderData($data);
        $validated['status'] = self::STATUS_PENDING;
        $validated['manager_id'] = Auth::id();

        return self::create($validated);
    }

    public static function updateOrder(array $data, int $id): self
    {
        $order = self::findOrFail($id);
    
        // Валидация данных
        $validated = self::validateOrderData($data);
    
        // Обновление полей заказа
        $order->update([
            'client_name' => $validated['client_name'],
            'client_email' => $validated['client_email'],
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'status' => $data['status'] ?? $order->status, // Если статус не передан, оставляем текущий
        ]);
    
        return $order;
    }

    public static function filterOrders(array $filters)
    {
        $query = self::query();

        if (isset($filters['status']) && array_key_exists($filters['status'], self::$statuses)) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['client_name'])) {
            $query->where('client_name', 'like', '%' . $filters['client_name'] . '%');
        }

        return $query->get();
    }

    public static function deleteOrderById(int $id): void
    {
            $order = self::findOrFail($id);
            $order->delete();
    }
    public function canBeEdited(): bool
    {
        return $this->status !== self::STATUS_COMPLETED && 
               $this->status !== self::STATUS_CANCELLED;
    }

    public function getStatusText(): string
    {
        return self::$statuses[$this->status] ?? $this->status;
    }
}