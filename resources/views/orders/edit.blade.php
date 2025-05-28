@extends('layouts.app')

@section('title', 'Редактирование заказа')

@section('main_content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Редактирование заказа #{{ $order->id }}</h2>
                    <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                        {{ $order->getStatusText() }}
                    </span>
                </div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('orders.update', $order) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="client_name" class="form-label">Имя клиента</label>
                            <input type="text" class="form-control" id="client_name" name="client_name" 
                                value="{{ old('client_name', $order->client_name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="client_email" class="form-label">Email клиента</label>
                            <input type="email" class="form-control" id="client_email" name="client_email" 
                                value="{{ old('client_email', $order->client_email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="product_id" class="form-label">Товар</label>
                            <select class="form-select" id="product_id" name="product_id" required>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ $product->id == $order->product_id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->unit_price }} руб.)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Количество</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                value="{{ old('quantity', $order->quantity) }}" min="1" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Статус заказа</label>
                            <select class="form-select" id="status" name="status">
                                @foreach((new App\Models\Order)::$statuses as $key => $status)
                                    <option value="{{ $key }}" {{ $key == $order->status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                    </form>

                    <form id="delete-form" action="{{ route('orders.destroy', $order->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h3 class="h5 mb-0">Информация о товаре</h3>
                </div>
                <div class="card-body">
                    @if($order->product)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Название:</strong> {{ $order->product->name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Цена за единицу:</strong> {{ $order->product->unit_price }} руб.
                            </li>
                            <li class="list-group-item">
                                <strong>Категория:</strong> {{ $order->product->category->name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Доступное количество:</strong> {{ $order->product->quantity }} шт.
                            </li>
                        </ul>
                    @else
                        <div class="alert alert-warning mb-0">
                            Товар не найден
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    .list-group-item {
        padding: 1rem 1.25rem;
    }
</style>
@endsection