@extends('layouts.app')

@section('title', 'Главная')

@section('main_content')
<div class="container">
    <h1 class="mb-4">ERP Система "Сталь Мульти"</h1>
    
    @auth
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Заказы</h5>
                    <p class="display-4">{{ $ordersCount }}</p>
                    <a href="{{ route('orders.index') }}" class="btn btn-primary">Перейти</a>
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

    <div class="card">
        <div class="card-header">
            Последние производственные задачи
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Заказ</th>
                        <th>Статус</th>
                        <th>Срок</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentTasks as $task)
                    <tr>
                        <td>{{ $task->id }}</td>
                        <td>Заказ #{{ $task->order_id }}</td>
                        <td>
                            <span class="badge bg-{{ $task->status === 'completed' ? 'success' : 'warning' }}">
                                {{ $task->status === 'completed' ? 'Завершено' : 'В работе' }}
                            </span>
                        </td>
                        <td>{{ $task->end_date?->format('d.m.Y') ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="alert alert-info">
        <h4>Добро пожаловать!</h4>
        <p>Пожалуйста, <a href="{{ route('login') }}">войдите</a> или <a href="{{ route('register') }}">зарегистрируйтесь</a> для работы с системой.</p>
    </div>
    @endauth
</div>
@endsection