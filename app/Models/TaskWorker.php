<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $task_id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\ProductionTask $task
 * @property-read \App\Models\User $worker
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskWorker whereUserId($value)
 * @mixin \Eloquent
 */
class TaskWorker extends Model
{
    protected $fillable = [
        'task_id',
        'user_id',
    ];

    // Связь с заданием
    public function task()
    {
        return $this->belongsTo(ProductionTask::class);
    }

    // Связь с сотрудником
    public function worker()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
