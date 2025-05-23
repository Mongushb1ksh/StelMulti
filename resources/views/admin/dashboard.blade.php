@extends('layouts.app')

@section('title', 'Админ-панель')

@section('main_content')
<div class="container">
    <h1 class="mb-4">Админ-панель</h1>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Всего пользователей</h5>
                    <p class="display-4">{{ $stats['users'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Активных</h5>
                    <p class="display-4">{{ $stats['active_users'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body text-center">
                    <h5 class="card-title">Заблокированных</h5>
                    <p class="display-4">{{ $stats['blocked_users'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            Быстрые действия
        </div>
        <div class="card-body">
            <div class="d-flex gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn btn-primary">
                    Управление пользователями
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    Управление категориями
                </a>
            </div>
        </div>
    </div>
</div>
@endsection