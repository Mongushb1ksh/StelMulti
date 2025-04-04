@extends('layout')

@section('main_content')

    <div class="profile-container">
        <h2>Профиль пользователя</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="error-message">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-info">
            <p><strong>Имя: </strong>{{ $user->name }}</p>
            <p><strong>Email: </strong>{{ $user->email }}</p>
            <p><strong>Роль: </strong>{{ optional($user->role)->name ?? 'Нет роли' }}</p>
            <p><strong>Дата регистрации: </strong>{{ $user->created_at->format('d.m.Y') }}</p>
        </div>
    </div>

    <h3>Редактировать данные</h3>
    <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="name">Имя</label>
            <input type="text" name="name" id="name" value="{{ $user->name }}" required>
        </div>

        <div>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ $user->email }}" required>
        </div>

        <button type="submit">Сохранить изменения</button>
    </form>


    <h3>Сменить пароль</h3>
    <form action="{{ route('profile.change-password') }}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label for="current_password">Текущий пароль</label>
            <input type="password" name="current_password" id="current_password" required>
        </div>

        <div>
            <label for="new_password">Новый пароль</label>
            <input type="password" name="new_password" id="new_password" required>
        </div>

        <div>
            <label for="new_password_confirmation">Подтвердите новый пароль</label>
            <input type="password" name="new_password_confirmation" id="new_password_confirmation" required>
        </div>

        <button type="submit">Сменить пароль</button>
    </form>

@endsection
