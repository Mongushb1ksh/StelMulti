@extends('layout')

@section('main_content')
<div class="admin-container">
    <h2>Список заказов</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(auth()->user()->role->name === 'Admin')
    <form method="GET" class="filters">
        <label for="status">Статус:</label>
        <select name="status" id="status">
            <option value="">Все</option>
            <option value="new">Новый</option>
            <option value="processing">В обработке</option>
            <option value="production">В производстве</option>
            <option value="completed">Готово</option>
            <option value="shipped">Отгружено</option>
        </select>

        <label for="client">Клиент:</label>
        <select name="client" id="client">
            <option value="">Все</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Применить фильтры</button>
        <a type="button" class="btn btn-primary" href="/orders/create">Добавить заказ</a>
    </form>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Статус</th>
                <th>Общая стоимость</th>
                <th>Дата создания</th>
            
                <th>
                @if(auth()->user()->role->name === 'Admin')
                    Действия
                @endif   
                </th>
                 
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
                        @if(auth()->user()->role->name !== 'Client')
                        <a href="{{ route('orders.show', $order) }}" class="btn btn-primary">Просмотр</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
</div>
@endsection