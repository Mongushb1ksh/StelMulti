<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $order_id
 * @property string $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskMaterial> $materials
 * @property-read int|null $materials_count
 * @property-read \App\Models\Order $order
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\TaskWorker> $workers
 * @property-read int|null $workers_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask whereOrderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ProductionTask whereUpdatedAt($value)
 * @mixin \Eloquent
 */
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
        return $this->hasMany(TaskMaterial::class);
    }

    public function workers()
    {
        return $this->hasMany(TaskWorker::class);
    }


}
