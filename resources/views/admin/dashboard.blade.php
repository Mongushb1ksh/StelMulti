@extends('layouts.app')

@section('title', 'Админ панель')

@section('main_content')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <h1>Админ панель</h1>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">Пользователи</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <div class="card">
    <div class="card-header">
</div>
    <div class="container py-4">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @yield('content')
    </div>
@endsection