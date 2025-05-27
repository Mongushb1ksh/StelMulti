@extends('admin.dashboard')

@section('content')
<div class="card">

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Всего пользователей</h5>
                        <p class="card-text">{{ $stats['total_users']}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Активных</h5>
                        <p class="card-text">{{ $stats['active_users']}}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Ожидают подтверждения</h5>
                        <p class="card-text">{{ $stats['pending_users']}}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3>Список пользователей</h3>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Создать пользователя</a>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Email</th>
                    <th>Статус</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->is_blocked)
                            <span class="badge bg-warning">Ожидает</span>
                        @else
                            <span class="badge bg-success">Активен</span>
                        @endif
                    </td>
                    <td class="d-flex">
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary me-2">Редактировать</a>
                        @if($user->is_blocked)
                            <form action="{{ route('admin.users.approve', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success me-2">Одобрить</button>
                            </form>
                        @else
                            <form action="{{ route('admin.users.block', $user) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-warning me-2">Заблокировать</button>
                            </form>
                        @endif
                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection