<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'status' => 'required|string|in:pending,processing,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'client_name.required' => 'Имя клиента обязательно',
            'client_name.string' => 'Имя должно быть строкой',
            'client_name.max' => 'Имя не должно превышать 255 символов',
            'client_email.required' => 'Email клиента обязателен',
            'client_email.email' => 'Введите корректный email',
            'product_id.required' => 'Товар обязателен',
            'product_id.exists' => 'Товар не найден',
            'quantity.required' => 'Количество обязательно',
            'quantity.integer' => 'Количество должно быть целым числом',
            'quantity.min' => 'Минимальное количество - 1',
            'status.required' => 'Статус обязателен',
            'status.in' => 'Недопустимый статус заказа',
        ];
    }
}