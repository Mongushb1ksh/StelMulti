@extends('layouts.app')
@section('title', 'Сотрудники - Администрирование')
@section('main_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Управление сотрудниками</h1>
        <a href="{{ route('admin.employees.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Добавить сотрудника
        </a>
    </div>

    <!-- Фильтры -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.employees.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Статус</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Все статусы</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Активный</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Неактивный</option>
                        <option value="terminated" {{ request('status') == 'terminated' ? 'selected' : '' }}>Уволен</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="position" class="form-label">Должность</label>
                    <input type="text" name="position" id="position" class="form-control" 
                           value="{{ request('position') }}" placeholder="Поиск по должности">
                </div>
                <div class="col-md-3">
                    <label for="name" class="form-label">Имя</label>
                    <input type="text" name="name" id="name" class="form-control" 
                           value="{{ request('name') }}" placeholder="Поиск по имени">
                </div>
                <div class="col-md-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" name="email" id="email" class="form-control" 
                           value="{{ request('email') }}" placeholder="Поиск по email">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Применить фильтры
                    </button>
                    <a href="{{ route('admin.employees.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Очистить
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица сотрудников -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Фото</th>
                            <th>Имя</th>
                            <th>Email</th>
                            <th>Должность</th>
                            <th>Отдел</th>
                            <th>Статус</th>
                            <th>Дата приема</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($employees as $employee)
                        <tr>
                            <td>{{ $employee->id }}</td>
                            <td>
                                @if($employee->photo)
                                    <img src="{{ asset('storage/' . $employee->photo) }}" 
                                         alt="Фото" class="rounded-circle" width="40" height="40">
                                @else
                                    <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center" 
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.employees.show', $employee) }}" class="text-decoration-none">
                                    {{ $employee->name }}
                                </a>
                            </td>
                            <td>{{ $employee->email }}</td>
                            <td>{{ $employee->position }}</td>
                            <td>{{ $employee->department ?? 'Не указан' }}</td>
                            <td>
                                <span class="badge bg-{{ $employee->status === 'active' ? 'success' : ($employee->status === 'inactive' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($employee->status) }}
                                </span>
                            </td>
                            <td>{{ $employee->hire_date ? $employee->hire_date->format('d.m.Y') : 'Не указана' }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.employees.show', $employee) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.employees.edit', $employee) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($employee->status === 'active')
                                    <form action="{{ route('admin.employees.terminate', $employee) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Уволить сотрудника?')">
                                            <i class="fas fa-user-times"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">Сотрудники не найдены</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($employees->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $employees->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
