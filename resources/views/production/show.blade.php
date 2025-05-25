@extends('layouts.app')

@section('title', 'Производственная задача #' . $productionTask->id)

@section('main_content')
    <div>
        <h1>Производственная задача #{{ $productionTask->id }}</h1>
        <div>

        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Информация о задаче</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Заказ:</strong> 
                    <a>
                        Заказ #{{ $productionTask->order_id }}
                    </a>
                </li>
                <li class="list-group-item">
                    <strong>Статус:</strong>
                    <span class="badge bg-{{ $productionTask->status === 'completed' ? 'success' : 'warning' }}">
                        {{ $productionTask->getStatusText() }}
                    </span>
                </li>
                <li class="list-group-item">
                    <strong>Дата начала:</strong> {{ $productionTask->start_date}}
                </li>
                <li class="list-group-item">
                    <strong>Дата завершения:</strong> 
                    {{ $productionTask->end_date ? $productionTask->end_date : 'Не завершено' }}
                </li>
                <li class="list-group-item">
                    <strong>Контроль качества:</strong> 
                    {{ $productionTask->quality_check ?? 'Не указано' }}
                </li>
            </ul>
        </div>
    </div>

    @if($productionTask->status !== App\Models\ProductionTask::STATUS_COMPLETED)
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Завершить задачу</h5>
                <form  method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="quality_check" class="form-label">Результаты контроля качества</label>
                        <textarea class="form-control" id="quality_check" name="quality_check" rows="3" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Завершить задачу</button>
                </form>
            </div>
        </div>
    @endif
@endsection