<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\Validator;


class ProductionTask extends Model
{
    protected $fillable = [
        'order_id',
        'status',
        'notes',
    ];


    public function order(){
        return $this->belongsTo((Order::class));
    }


    public function materials()
    {
        return $this->hasMany(TaskMaterial::class , 'task_id');
    }

    public function workers()
    {
        return $this->hasMany(TaskWorker::class, 'task_id');
    }

    public static function validateTaskData(array $data)
    {
        \Log::info('Validation input data:', $data); // Логирование входных данных
        $validator = Validator::make($data, [
            'order_id' => 'required|exists:orders,id',
            'materials' => 'required|array',
            'materials.*.id' => 'required|exists:products,id',
            'materials.*.quantity_required' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        }
    }

    public static function validateStatusUpdate(string $status)
    {
        $allowedStatuses = ['queued', 'in_progress', 'completed'];

        if (!in_array($status, $allowedStatuses)) {
            throw new Exception('Недопустимый статус.');
        }
    }


    public function startTask()
    {
        if ($this->status !== 'pending') {
            throw new Exception('Задача уже начата или завершена.');
        }

        $this->update(['status' => 'in_progress']);
    }

    public static function createNewTask(array $data)
    {
        self::validateTaskData($data);

        $task = self::create([
            'order_id' => $data['order_id'],
            'status' => 'queued',
        ]);

        \Log::info('Created task:', $task->toArray()); // Логирование созданной задачи

        // Привязка материалов к задаче
        foreach ($data['materials'] as $material) {
            $task->materials()->create([
                'product_id' => $material['id'],
                'quantity_required' => $material['quantity_required'],
            ]);
        }

        \Log::info('Attached materials:', $data['materials']); // Логирование привязанных материалов

        return $task;
    }


    public function updateTaskStatus(string $status)
    {
        self::validateStatusUpdate($status);

        $this->update(['status' => $status]);

        // Если задача завершена, выполняем дополнительные действия
        if ($status === 'completed') {
            $this->completeTask();
        }
    }
    protected function completeTask()
    {
        // Уменьшаем количество материалов на складе
        foreach ($this->materials as $material) {
            $product = $material->product;
            $product->decreaseQuantity($material->quantity_required);
        }
    }
}
