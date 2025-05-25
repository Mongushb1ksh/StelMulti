@extends('layouts.app')

@section('main_content')
<div class="production-container">
    <h2>Создание производственного задания</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('production.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="order_id" class="form-label">Заказ</label>
            <select name="order_id" id="order_id" class="form-select" required>
                <option value="">Выберите заказ</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">Заказ #{{ $order->id }} - {{ $order->product->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Статус</label>
            <select name="status" id="status" class="form-select" required>
                @foreach((new App\Models\ProductionTask)::$statuses as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="start_date" class="form-label">Дата начала</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="quality_check" class="form-label">Примечания по качеству</label>
            <textarea name="quality_check" id="quality_check" class="form-control" rows="3"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Создать задание</button>
        <a href="{{ route('production.index') }}" class="btn btn-secondary">Отмена</a>
    </form>
</div>
@endsection