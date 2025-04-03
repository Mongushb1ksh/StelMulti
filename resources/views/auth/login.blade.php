@extends('layout')

@section('main_content')


<div class="form-container">
    <h2>Вход в систему</h2>


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

    <form method="POST" action="/login/show">
        @csrf
        <input type="email" name="email" placeholder="Email" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Войти</button>
        <a href="/register">Зарегистрироваться</a>
    </form>
</div>


@endsection