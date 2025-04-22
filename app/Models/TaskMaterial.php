<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $task_id
 * @property int $material_id
 * @property int $quantity_required
 * @property int $quantity_used
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product $material
 * @property-read \App\Models\ProductionTask $task
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial whereMaterialId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial whereQuantityRequired($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial whereQuantityUsed($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|TaskMaterial whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class TaskMaterial extends Model
{
    protected $fillable = [
        'task_id',
        'material_id',
        'quantity_required',
        'quantity_used',
    ];

    // Связь с заданием
    public function task()
    {
        return $this->belongsTo(ProductionTask::class);
    }

    // Связь с материалом
    public function material()
    {
        return $this->belongsTo(Product::class, 'material_id');
    }
}
