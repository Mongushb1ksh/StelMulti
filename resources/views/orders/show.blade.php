@extends('layouts.app')

@section('main_content')
<div class="order-details">
    <h2>Детали заказа #{{ $order->id }}</h2>

    <p><strong>Клиент:</strong> {{ $order->user->name }}</p>
    <p><strong>Статус:</strong> {{ $order->status }}</p>
    <p><strong>Общая стоимость:</strong> {{ $order->total_price ?? 'N/A' }}</p>
    <p><strong>Дата создания:</strong> {{ $order->created_at->format('d.m.Y') }}</p>

    <h3>Товары</h3>
    <table class="table">
        <thead>
            <tr>
                <th>Название</th>
                <th>Количество</th>
                <th>Цена за единицу</th>
                <th>Общая стоимость</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }} руб.</td>
                    <td>{{ $item->quantity * $item->price }} руб.</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Форма обновления статуса -->
    <form action="{{ route('orders.update-status', $order) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="status">Обновить статус:</label>
        <select name="status" id="status" required>
            <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>Новый</option>
            <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>В обработке</option>
            <option value="production" {{ $order->status === 'production' ? 'selected' : '' }}>В производстве</option>
            <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Готово</option>
            <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Отгружено</option>
        </select>
        <button type="submit" class="btn btn-primary">Обновить</button>
    </form>
</div>

<style>
.order-container, .order-details {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #ffffff;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Заголовки */
.order-container h2, .order-details h2 {
    font-size: 24px;
    color: #333333;
    margin-bottom: 20px;
}

/* Таблица */
.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
}

.table th, .table td {
    border: 1px solid #dddddd;
    padding: 10px;
    text-align: left;
}

.table th {
    background-color: #f8f9fa;
    font-weight: bold;
}

/* Форма */
.form-group {
    margin-bottom: 15px;
}

label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}

input[type="text"], input[type="number"], select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
    border: 1px solid #dddddd;
    border-radius: 4px;
}

button {
    display: inline-block;
    padding: 10px 20px;
    font-size: 16px;
    color: #ffffff;
    background-color: #007bff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button:hover {
    background-color: #0056b3;
}


</style>

@endsection


