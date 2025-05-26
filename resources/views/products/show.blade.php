@extends('layouts.app')

@section('title', 'Просмотр товара: ' . $product->name)

@section('main_content')
<div class="container py-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="mb-0">{{ $product->name }}</h2>
                        <div class="badge bg-primary text-white">
                            {{ $product->category->name ?? 'Без категории' }}
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <div class="product-image-placeholder bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px; border-radius: 4px;">
                                <span class="text-muted">Изображение товара</span>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <h4 class="text-muted mb-3">Описание</h4>
                            <p class="card-text">{{ $product->description ?? 'Описание отсутствует' }}</p>
                            
                            <div class="row mt-4">
                            @if(optional(auth()->user())->role?->name === 'Warehouse Manager')
                                <div class="col-6">
                                    <h5 class="text-muted">Количество на складе</h5>
                                    <p class="h4">{{ $product->quantity }} шт.</p>
                                </div>
                            @endif
                                <div class="col-6">
                                    <h5 class="text-muted">Цена за единицу</h5>
                                    <p class="h4 text-success">{{ number_format($product->unit_price, 2) }} ₽</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if(optional(auth()->user())->role?->name === 'Warehouse Manager')
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-between">
                        <small class="text-muted">
                            Добавлен: {{ $product->created_at->format('d.m.Y H:i') }}
                        </small><br>
                        <small class="text-muted">
                            Обновлен: {{ $product->updated_at->format('d.m.Y H:i') }}
                        </small>
                    </div>
                </div>
               @endif
            </div>
            
            @if(optional(auth()->user())->role?->name === 'Warehouse Manager')
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Заказы с этим товаром</h3>
                </div>
                <div class="card-body">
                    @if($product->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID заказа</th>
                                        <th>Клиент</th>
                                        <th>Количество</th>
                                        <th>Статус</th>
                                        <th>Дата</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($product->orders as $order)
                                    <tr>
                                        <td>
                                            <a href="{{ route('orders.show', $order->id) }}">
                                                #{{ $order->id }}
                                            </a>
                                        </td>
                                        <td>{{ $order->client_name }}</td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>
                                            <span class="badge 
                                                @if($order->status == 'completed') bg-success
                                                @elseif($order->status == 'cancelled') bg-danger
                                                @else bg-warning text-dark
                                                @endif">
                                                {{ $order->getStatusText() }}
                                            </span>
                                        </td>
                                        <td>{{ $order->created_at->format('d.m.Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            Этот товар еще не заказывали
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Действия</h3>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.edit', $product->id) }}" 
                           class="btn btn-primary btn-lg">
                            <i class="fas fa-edit"></i> Редактировать
                        </a>
                        
                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" 
                              onsubmit="return confirm('Вы уверены, что хотите удалить этот товар?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                <i class="fas fa-trash"></i> Удалить товар
                            </button>
                        </form>
                        
                        <a href="{{ route('products.index') }}" 
                           class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-arrow-left"></i> Назад к списку
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-white">
                    <h3 class="mb-0">Статистика</h3>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Всего заказов
                            <span class="badge bg-primary rounded-pill">
                                {{ $product->orders->count() }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Продано единиц
                            <span class="badge bg-success rounded-pill">
                                {{ $product->orders->sum('quantity') }}
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Общая выручка
                            <span class="badge bg-info rounded-pill">
                                {{ number_format($product->orders->sum('quantity') * $product->unit_price, 2) }} ₽
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .product-image-placeholder {
        border: 1px dashed #dee2e6;
    }
    .card {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .card-header {
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
</style>
@endsection