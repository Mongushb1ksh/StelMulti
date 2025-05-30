<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class ProductionTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'status',
        'start_date',
        'end_date',
        'quality_check',
        'end_date'
    ];

    protected $dates = ['start_date', 'end_date'];

    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';

    public static $statuses = [
        self::STATUS_IN_PROGRESS => 'В работе',
        self::STATUS_COMPLETED => 'Завершено',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public static function validateData(array $data)
    {
        $validator = Validator::make($data, [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string|in:queued,in_progress,completed',
            'start_date' => 'required|date',
            'quality_check' => 'nullable|string',
        ]);
        

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createTask(array $data): self
    {
        $validated = self::validateData($data, true);
        return self::create($validated);
    }

    public static function updateTask(array $data, int $id): self
    {
        $productionTask = self::findOrFail($id);
        $validated = self::validateData($data, true);
        $productionTask->update($validated);
        return $productionTask;
    }

    public function completeTask(string $qualityCheck): void
    {
        $this->update([
            'status' => self::STATUS_COMPLETED,
            'end_date' => now(),
            'quality_check' => $qualityCheck
        ]);
        
        if ($this->order) {
            $this->order->update(['status' => Order::STATUS_COMPLETED]);
        }   
    }

    public static function filterTasks(array $filters)
    {
        $query = self::query();
        if (isset($filters['status']) && array_key_exists($filters['status'], self::$statuses)) {
            $query->where('status', $filters['status']);
        }
        return $query->get();
    }

    public function getStatusText(): string
    {
        return self::$statuses[$this->status] ?? $this->status ?? 'Неизвестный статус';
    }

    public static function deleteTask(int $id): void
    {
        $productionTask = self::findOrFail($id);
        $productionTask->delete();
    }
}