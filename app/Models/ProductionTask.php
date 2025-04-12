<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
