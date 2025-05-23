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
                <li><a href="/login" class="{{ request()->is('login') ? 'active' : '' }}">Вход</a></li>
                <li><a href="/register" class="{{ request()->is('register') ? 'active' : '' }}">Регистрация</a></li>
            @else
                <li><a href="/home" class="{{ request()->is('home') ? 'active' : '' }}">Главная</a></li>
                <li><a href="/catalog" class="{{ request()->is('catalog') ? 'active' : '' }}">Готовые изделия</a></li>
                <li><a href="/orders" class="{{ request()->is('orders') ? 'active' : '' }}">Заказы</a></li>
                <li><a href="/stock" class="{{ request()->is('stock') ? 'active' : '' }}">Склад</a></li>
                <li><a href="/production" class="{{ request()->is('production') ? 'active' : '' }}">Производство</a></li>
                @if(optional(auth()->user())->role?->name === 'Admin')
                    <li><a href="/admin/users" class="{{ request()->is('admin/users') ? 'active' : '' }}">Пользователи</a></li>
                @endif
                <li><a href="/profile" class="{{ request()->is('profile') ? 'active' : '' }}">Профиль</a></li>
            @endif
        </ul>
    </aside>

    <main class="main-content">
        @yield('main_content')
    </main>

</body>
</html>