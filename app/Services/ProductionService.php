<?php

namespace App\Services;

use App\Models\ProductionTask;
use App\Models\TaskMaterial;
use App\Models\TaskWorker;

class ProductionService
{
    public function createTask(array $data): ProductionTask
    {
        // Создаем задание
        $task = ProductionTask::create([
            'order_id' => $data['order_id'],
            'status' => 'queued',
        ]);

        // Добавляем материалы для задания
        foreach ($data['materials'] as $material) {
            TaskMaterial::create([
                'task_id' => $task->id,
                'material_id' => $material['id'],
                'quantity_required' => $material['quantity_required'],
            ]);
        }

        // Назначаем сотрудников
        foreach ($data['workers'] as $workerId) {
            TaskWorker::create([
                'task_id' => $task->id,
                'user_id' => $workerId,
            ]);
        }

        return $task;
    }

    public function updateTaskStatus(ProductionTask $task, string $status): void
    {
        $task->update(['status' => $status]);
    }

    public function performQualityCheck(ProductionTask $task, ?string $notes): void
    {
        if ($task->status !== 'completed') {
            throw new \Exception('Задание еще не завершено.');
        }

        $task->update([
            'status' => 'quality_checked',
            'notes' => $notes,
        ]);
    }
}