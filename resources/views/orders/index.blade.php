@extends('layouts.app')

@section('title', 'Управление заказами')

@section('main_content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Заказы</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Создать заказ</a>
    </div>

    <form method="GET" action="{{ route('orders.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="status" class="form-label">Статус</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Все</option>
                    @foreach ((new App\Models\Order)::$statuses as $key => $value)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label for="client_name" class="form-label">Имя клиента</label>
                <input type="text" name="client_name" id="client_name" class="form-control" 
                    value="{{ request('client_name') }}" placeholder="Поиск по имени">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Применить</button>
                <a href="{{ route('orders.index') }}" class="btn btn-secondary">Очистить</a>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Клиент</th>
                            <th>Товар</th>
                            <th>Количество</th>
                            <th>Статус</th>
                            <th>Менеджер</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->client_name }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>
                                <p class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ $order->getStatusText() }}
                                </p>
                            </td>
                            <td>{{ $order->manager->name }}</td>
                            <td class="table-action">
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Просмотр</a>
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary">Редактировать</a>
                                <form action="{{ route('orders.destroy', $order) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-primary"
                                            onclick="return confirm('Удалить этот заказ?')">
                                            Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $orders->links() }}
        </div>
    </div>
</div>
<style>
    .table-action{
        display: flex;
        gap: 2%;
        align-items: center;
        flex-wrap: wrap;
    }
    table{
        max-width: 1920px;
    }
    .card{
        max-width: 1920px;
    }
</style>
@endsection