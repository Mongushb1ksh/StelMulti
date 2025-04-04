@extends('layout')

@section('main_content')
<div class="admin-container">
    <h2>Список заказов</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Статус</th>
                <th>Общая стоимость</th>
                <th>Дата создания</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->total_price ?? 'N/A' }}</td>
                    <td>{{ $order->created_at->format('d.m.Y') }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Просмотр</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
</div>
@endsection