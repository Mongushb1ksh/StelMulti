<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductionTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|string|in:queued,in_progress,completed',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date',
            'quality_check' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'order_id.required' => 'Заказ обязателен',
            'order_id.exists' => 'Заказ не найден',
            'status.required' => 'Статус обязателен',
            'status.in' => 'Недопустимый статус задачи',
            'start_date.required' => 'Дата начала обязательна',
            'start_date.date' => 'Некорректная дата начала',
            'end_date.date' => 'Некорректная дата завершения',
            'quality_check.string' => 'Примечание должно быть строкой',
        ];
    }
}