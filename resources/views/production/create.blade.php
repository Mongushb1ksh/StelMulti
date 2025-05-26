@extends('layouts.app')

@section('title', 'Создание производственной задачи')

@section('main_content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-white">
                    <h2 class="h5 mb-0">Создание новой задачи</h2>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('production.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="order_id" class="form-label">Заказ</label>
                            <select class="form-select" id="order_id" name="order_id" required>
                                <option value="">Выберите заказ</option>
                                @foreach($orders as $order)
                                    <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                        Заказ #{{ $order->id }} - {{ $order->product->name }} ({{ $order->quantity }} шт.)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Статус</label>
                            <select class="form-select" id="status" name="status" required>
                                @foreach(App\Models\ProductionTask::$statuses as $key => $status)
                                    <option value="{{ $key }}" {{ old('status') == $key ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="start_date" class="form-label">Дата начала</label>
                            <input type="datetime-local" class="form-control" id="start_date" name="start_date" 
                                   value="{{ old('start_date', now()->format('Y-m-d\TH:i')) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="quality_check" class="form-label">Примечания по качеству</label>
                            <textarea class="form-control" id="quality_check" name="quality_check" rows="3">{{ old('quality_check') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Создать задачу
                            </button>
                            <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Назад
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection