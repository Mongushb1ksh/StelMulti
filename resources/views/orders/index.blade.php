@extends('layouts.app')

@section('title', 'Управление заказами')

@section('main_content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Заказы</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">Создать заказ</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
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
                            <td>{{ $order->client_name }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>
                                <p class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ $order->getStatusText() }}
                                </p>
                            </td>
                            <td>{{ $order->manager->name }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Просмотр</a>
                                <a href="{{ route('orders.edit', $order) }}" class="btn btn-primary">Редактировать</a>
                                <form action="{{ route('orders.destroy', $order) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger"
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
@endsection