@extends('layout')


@section('main_content')
<div class="admin-container">
    <h2>Управление пользователями</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Роль</th>
                <th>Статус</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                @if($user->id !== auth()->id())
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ $user->role?->name ?? 'Нет роли' }}</td>
                    <td>
                        @if($user->is_approved)
                            <span style="color: green;">Одобрен</span>
                        @else
                            <span style="color: red;">Не одобрен</span>
                        @endif
                    </td>
                    <td>
                        @if(!$user->is_approved)
                            <form action="{{ route('admin.users.approved', $user) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-success">Одобрить</button>
                            </form>
                        @endif
                        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-primary">Редактировать</a>
                    </td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    {{ $users->links() }}

@endsection