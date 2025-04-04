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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Клиент
            $table->string('status')->default('new'); // Статус заказа
            $table->decimal('total_price', 10, 2)->nullable(); // Общая стоимость
            $table->text('notes')->nullable(); // Примечания
            $table->timestamp('completed_at')->nullable(); // Дата завершения
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
