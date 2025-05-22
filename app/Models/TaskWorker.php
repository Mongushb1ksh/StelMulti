<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskWorker extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
    ];

    // Связь с заданием
    public function task()
    {
        return $this->belongsTo(ProductionTask::class, 'task_id');
    }

    // Связь с сотрудником
    public function worker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
