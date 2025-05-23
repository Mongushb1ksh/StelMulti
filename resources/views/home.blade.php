@extends('layout')



@section('header_content')

<div class="user-info">
    Добро пожаловать, {{ auth()->user()->name }}
</div>


@endsection

@section('main_content')
    <h2>Текущие задачи</h2>
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
                                <li>{{ $material->material->name }} ({{ $material->quantity_required }})</li>
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
                        <a href="{{ route('production.index', $task) }}" class="btn  btn-primary">Просмотр</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>


   
@endsection