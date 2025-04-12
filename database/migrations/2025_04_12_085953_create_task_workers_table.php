<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('task_workers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id') // Тип данных: unsignedBigInteger
                  ->constrained('production_tasks') // Связь с таблицей production_tasks
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users') // Связь с таблицей users
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_workers');
    }
};
