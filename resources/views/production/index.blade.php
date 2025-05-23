@extends('layout')

@section('main_content')
<div class="production-container">
    <h2>Управление производством</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a href="{{ route('production.create') }}" class="btn btn-primary">Создать задание</a>

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Заказ</th>
                <th>Статус</th>
                <th>Материалы</th>
                <th>Сотрудники</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
                <tr>
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->order->id }}</td>
                    <td>{{ $task->status }}</td>
                    <td>
                        <ul>
                            @foreach($task->materials as $material)
                                <li>{{ $material->material->name }} (Требуется: {{ $material->quantity_required }})</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <ul>
                            @foreach($task->workers as $worker)
                                <li>{{ $worker->worker->name }}</li>
                            @endforeach
                        </ul>
                    </td>
                    <td>
                        <form action="{{ route('production.update-status', $task) }}" method="POST" style="display:inline;">
                            @csrf
                            <select name="status" onchange="this.form.submit()">
                                <option value="queued" {{ $task->status === 'queued' ? 'selected' : '' }}>В очереди</option>
                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>В работе</option>
                                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Завершено</option>
                            </select>
                        </form>

                        @if($task->status === 'completed')
                            <form action="{{ route('production.quality-check', $task) }}" method="POST" style="display:inline;">
                                @csrf
                                <input type="text" name="quality_notes" placeholder="Примечания к проверке">
                                <button type="submit" class="btn btn-primary">Проверить качество</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection