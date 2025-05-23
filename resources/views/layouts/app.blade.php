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
            <form action="{{ route('logout') }}" method="POST">
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
                <li><a class="nav-link" href="{{ route('login') }}">Вход</a></li>
                <li><a class="nav-link" href="{{ route('register') }}">Регистрация</a></li>
            @else
                <li><a href="{{ route('home') }}" class="{{ request()->is('home') ? 'active' : '' }}">Главная</a></li>
                <li> <a class="nav-link" href="{{ route('products.index') }}">Продукция</a></li>
                <li><a class="nav-link" href="{{ route('orders.index') }}">Заказы</a></li>
                <li><a class="nav-link" href="{{ route('stock.index') }}">Склад</a></li>
                <li><a class="nav-link" href="{{ route('production.index') }}">Производство</a></li>
                @if(optional(auth()->user())->role?->name === 'Admin')
                    <li><a href="/admin/users" class="{{ request()->is('admin/users') ? 'active' : '' }}">Пользователи</a></li>
                @endif
                <li><a href="/profile" class="{{ route('profile.show') }}"">Профиль</a></li>
            @endif
        </ul>
    </aside>

    <main class="main-content">
        @yield('main_content')
    </main>

    <footer class="bg-light py-3 mt-4">
        <div class="container text-center">
            &copy; {{ date('Y') }} ERP Система "Сталь Мульти"
        </div>
    </footer>
</body>
</html>