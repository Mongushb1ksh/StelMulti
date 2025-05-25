@extends('layouts.app')

@section('title', 'Производственные задачи')

@section('main_content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Производственные задачи</h1>
        <a href="{{ route('production.create') }}" class="btn btn-primary">Создать задачу</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>№</th>
                            <th>Заказ</th>
                            <th>Товар</th>
                            <th>Статус</th>
                            <th>Начало</th>
                            <th>Завершение</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tasks as $task)
                        <tr>
                            <td>{{ $task->id }}</td>
                            <td>Заказ №{{ $task->order_id }}</td>
                            <td>{{ $task->order->product->name }}</td>
                            <td>
                                <span class="badge bg-{{ $task->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ $task->getStatusText() }}
                                </span>
                            </td>
                            <td>{{ $task->end_date ? $task->end_date->format('d.m.Y')}}</td>
                            <td>{{ $task->end_date ? $task->end_date->format('d.m.Y') : '-' }}</td>
                            <td>
                                <a href="{{ route('production.show', $task) }}" class="btn btn-primary">Просмотр</a>
                                <a href="{{ route('production.edit', $task) }}" class="btn btn-primary">Редактировать</a>
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $tasks->links() }}
        </div>
    </div>
</div>
@endsection