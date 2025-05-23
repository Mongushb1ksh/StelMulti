<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'unit_price' => 'required|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Название товара обязательно',
            'name.string' => 'Название должно быть строкой',
            'name.max' => 'Название не должно превышать 255 символов',
            'quantity.required' => 'Количество обязательно',
            'quantity.integer' => 'Количество должно быть целым числом',
            'quantity.min' => 'Количество не может быть отрицательным',
            'category_id.required' => 'Категория обязательна',
            'category_id.exists' => 'Категория не найдена',
            'unit_price.required' => 'Цена обязательна',
            'unit_price.numeric' => 'Цена должна быть числом',
            'unit_price.min' => 'Цена не может быть отрицательной',
        ];
    }
}