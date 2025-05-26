@extends('layouts.app')

@section('title', 'Редактирование производственной задачи')

@section('main_content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h2 class="h5 mb-0">Редактирование задачи #{{ $productionTask->id }}</h2>
                    <span class="badge bg-{{ $productionTask->status === 'completed' ? 'success' : 'warning' }}">
                        {{ $productionTask->getStatusText() }}
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

                    <form method="POST" action="{{ route('production.update', $productionTask) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="order_id" class="form-label">Связанный заказ</label>
                            <select class="form-select" id="order_id" name="order_id" required>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" 
                                        {{ $order->id == $productionTask->order_id ? 'selected' : '' }}>
                                        Заказ #{{ $order->id }} - {{ $order->product->name }} ({{ $order->quantity }} шт.)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Статус</label>
                            <select class="form-select" id="status" name="status" required>
                                @foreach((new App\Models\ProductionTask)::$statuses as $key => $status)
                                    <option value="{{ $key }}" 
                                        {{ $key == $productionTask->status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_date" class="form-label">Дата начала</label>
                                <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                                value="{{ old('start_date', $productionTask->start_date) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="end_date" class="form-label">Дата завершения</label>
                                <input type="datetime-local" class="form-control" id="end_date" name="end_date" 
                                value="{{ old('end_date', $productionTask->end_date) }}">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="quality_check" class="form-label">Проверка качества</label>
                            <textarea class="form-control" id="quality_check" name="quality_check" rows="3">{{ old('quality_check', $productionTask->quality_check) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Сохранить изменения
                            </button>
                            
                            @if($productionTask->status !== \App\Models\ProductionTask::STATUS_COMPLETED)
                                <a href="{{ route('production.complete', $productionTask->id) }}" class="btn btn-success">
                                    <i class="fas fa-check-circle"></i> Завершить задачу
                                </a>
                            @endif
                            
                            <button type="button" class="btn btn-danger" 
                                    onclick="if(confirm('Вы уверены, что хотите удалить эту задачу?')) {
                                        document.getElementById('delete-form').submit();
                                    }">
                                <i class="fas fa-trash-alt"></i> Удалить
                            </button>
                        </div>
                    </form>

                    <form id="delete-form" action="{{ route('production.destroy', $productionTask->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
            
            <!-- Информация о заказе -->
            <div class="card mt-4">
                <div class="card-header bg-white">
                    <h3 class="h5 mb-0">Информация о связанном заказе</h3>
                </div>
                <div class="card-body">
                    @if($productionTask->order)
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Клиент:</strong> {{ $productionTask->order->client_name }} ({{ $productionTask->order->client_email }})
                            </li>
                            <li class="list-group-item">
                                <strong>Товар:</strong> {{ $productionTask->order->product->name }}
                            </li>
                            <li class="list-group-item">
                                <strong>Количество:</strong> {{ $productionTask->order->quantity }} шт.
                            </li>
                            <li class="list-group-item">
                                <strong>Статус заказа:</strong> 
                                <span class="badge bg-{{ $productionTask->order->status === 'completed' ? 'success' : ($productionTask->order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                    {{ $productionTask->order->getStatusText() }}
                                </span>
                            </li>
                        </ul>
                    @else
                        <div class="alert alert-warning mb-0">
                            Связанный заказ не найден
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