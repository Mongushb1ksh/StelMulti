@extends('layouts.app')

@section('main_content')
<div class="order-details">
    <h2>Детали заказа #{{ $order->id }}</h2>
        <div>
            <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning">Редактировать</a>
            <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger" onclick="return confirm('Вы уверены?')">Удалить</button>
            </form>
        </div>

        <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Информация о заказе</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Клиент:</strong> {{ $order->client_name }} ({{ $order->client_email }})
                </li>
                <li class="list-group-item">
                    <strong>Товар:</strong> {{ $order->product->name }}
                </li>
                <li class="list-group-item">
                    <strong>Количество:</strong> {{ $order->quantity }}
                </li>
                <li class="list-group-item">
                    <strong>Статус:</strong>
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                        {{ $order->getStatusText() }}
                    </span>
                </li>
                <li class="list-group-item">
                    <strong>Менеджер:</strong> {{ $order->manager->name }}
                </li>
                <li class="list-group-item">
                    <strong>Дата создания:</strong> {{ $order->created_at }}
                </li>
            </ul>
        </div>
    </div>
    @if($order->productionTask)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Производственная задача</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <strong>Статус:</strong>
                        <span class="badge bg-{{ $order->productionTask->status === 'completed' ? 'success' : 'warning' }}">
                            {{ $order->productionTask->getStatusText() }}
                        </span>
                    </li>
                    <li class="list-group-item">
                        <strong>Дата начала:</strong> {{ $order->productionTask->start_date}}
                    </li>
                    @if($order->productionTask->end_date)
                        <li class="list-group-item">
                            <strong>Дата завершения:</strong> {{ $order->productionTask->end_date}}
                        </li>
                    @endif
                    @if($order->productionTask->quality_check)
                        <li class="list-group-item">
                            <strong>Контроль качества:</strong> {{ $order->productionTask->quality_check }}
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    @endif


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


