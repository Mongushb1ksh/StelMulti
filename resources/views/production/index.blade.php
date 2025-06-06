@extends('layouts.app')

@section('title', 'Производственные задачи')

@section('main_content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Производственные задачи</h1>
        <a href="{{ route('production.create') }}" class="btn btn-primary">Создать задачу</a>
    </div>
    <form method="GET" action="{{ route('production.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="status" class="form-label">Статус</label>
                <select name="status" id="status" class="form-select">
                    <option value="">Все</option>
                    @foreach (App\Models\ProductionTask::$statuses as $key => $value)
                        <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                            {{ $value }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Применить</button>
                <a href="{{ route('production.index') }}" class="btn btn-secondary">Очистить</a>
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
                            <th>Заказ</th>
                            <th>Товар</th>
                            <th>Статус</th>
                            <th>Начало</th>
                            <th>Завершение</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($productionTasks as $productionTask)
                        <tr>
                            <td>{{ $productionTask->id }}</td>
                            <td>Заказ №{{ $productionTask->order_id }}</td>
                            <td>{{ $productionTask->order->product->name }}</td>
                            <td>
                                <span class="badge bg-{{ $productionTask->status === 'completed' ? 'success' : 'warning' }}">
                                    {{ $productionTask->getStatusText() }}
                                </span>
                            </td>
                            <td>{{ $productionTask->start_date}}</td>
                            <td>{{ $productionTask->end_date}}</td>
                            <td>
                                <a href="{{ route('production.show', $productionTask) }}" class="btn btn-primary">Просмотр</a>
                                <a href="{{ route('production.edit', $productionTask) }}" class="btn btn-primary">Редактировать</a>
                            </td>
                        </tr>
                         @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $productionTasks->links() }}
        </div>
    </div>
</div>
@endsection