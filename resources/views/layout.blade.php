<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <title>@yield('title', 'ERP-Система')</title>
</head>
<body>
    <!-- Шапка -->
    <header>
        <h1>ERP-Система | Сталь Мульти</h1>
        @yield('header_content')
        @if(auth()->check())
        <button class="menu-toggle">
            <form action="/logout" method="POST">
                @csrf
                <button type="submit">
                    Выйти
                </button>
            </form>
            ☰
        </button>
        @endif
    </header>

    <aside class="sidebar">
        <ul>
            
            @if(!auth()->check())
            <li><a href="/login">Вход</a></li>
            <li><a href="/register">Регистрация</a></li>
            <li><a href="#">Настройки</a></li>
            @else
            <li><a href="/home">Главная</a></li>
            <li><a href="#">Клиенты</a></li>
            <li><a href="#">Продажи</a></li>
            <li><a href="#">Производство</a></li>
            <li><a href="#">Финансы</a></li>
            @endif
        </ul>
    </aside>

    <main class="main-content">
        @yield('main_content')
    </main>

</body>
</html>