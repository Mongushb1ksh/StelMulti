<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
