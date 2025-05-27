@extends('layouts.app')

@section('title', 'Главная')

@section('main_content')
<div class="container">
     @if(auth()->check())
    <h1 class="mb-4">Главная</h1>
    @endif
    @auth
    <div class="column">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Заказы</h5>
                    <p class="display-4">{{ $ordersCount }}</p>
                    @if(optional(auth()->user())->role?->name === 'Client Manager')
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Перейти</a>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Производство</h5>
                    <p class="display-4">{{ $tasksCount }}</p>
                    <a href="{{ route('production.index') }}" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Товары</h5>
                    <p class="display-4">{{ $productsCount }}</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Перейти</a>
                </div>
            </div>
        </div>
    </div>

    <div>
        <div class="card-header">
            Последние производственные задачи
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Заказ</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTasks as $productionTask)
                    <tr>
                        <td>{{ $productionTask->id }}</td>
                        <td>Заказ №{{ $productionTask->order_id }}</td>
                        <td>
                            <span class="badge bg-{{ $productionTask->status === 'completed' ? 'success' : 'warning' }}">
                                {{ $productionTask->status === 'completed' ? 'Завершено' : 'В работе' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <h4>Добро пожаловать!</h4>
        <p>Пожалуйста, <a style="text-decoration: none; color: #86b7fe;" href="{{ route('login') }}">войдите</a> или <a style="text-decoration: none; color: #86b7fe;" href="{{ route('register') }}">зарегистрируйтесь</a> для работы с системой.</p>
    </div>
    @endauth
</div>

<style>
    .column{
        display: flex;
        justify-content: space-around;
    }
    .card {
        
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .card-body {
        padding: 20px;
    }

    .col-md-4{
        width: 30%;
    }

    .card-title {
        font-size: 1.25rem;
        font-weight: bold;
        color: #333;
        margin-bottom: 15px;
    }

    .display-4 {
        font-size: 2.5rem;
        font-weight: bold;
        color: #007bff;
        margin-bottom: 20px;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
        padding: 10px 20px;
        font-size: 0.9rem;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    /* Адаптивность для мобильных устройств */
    @media (max-width: 768px) {
        .card {
            margin-bottom: 20px;
        }

        .display-4 {
            font-size: 2rem;
        }
    }
</style>
@endsection