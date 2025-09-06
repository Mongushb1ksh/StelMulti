@extends('layouts.app')

@section('main_content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Управление клиентами</h1>
        <a href="{{ route('customers.create') }}" class="btn btn-primary">
            <i class="fas fa-plus fa-sm"></i> Добавить клиента
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Фильтры -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Фильтры</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('customers.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Имя клиента</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ request('name') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ request('email') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="status">Статус</label>
                            <select class="form-control" id="status" name="status">
                                <option value="">Все статусы</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Активные</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Неактивные</option>
                                <option value="prospect" {{ request('status') === 'prospect' ? 'selected' : '' }}>Потенциальные</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">Применить</button>
                                <a href="{{ route('customers.index') }}" class="btn btn-secondary">Сбросить</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Список клиентов -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Список клиентов</h6>
        </div>
        <div class="card-body">
            @if($customers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Имя</th>
                                <th>Email</th>
                                <th>Телефон</th>
                                <th>Компания</th>
                                <th>Статус</th>
                                <th>Заказов</th>
                                <th>Выручка</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customers as $customer)
                            <tr>
                                <td>{{ $customer->id }}</td>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->phone ?? 'Не указан' }}</td>
                                <td>{{ $customer->company ?? 'Не указана' }}</td>
                                <td>
                                    @if($customer->status === 'active')
                                        <span class="badge badge-success">Активный</span>
                                    @elseif($customer->status === 'inactive')
                                        <span class="badge badge-secondary">Неактивный</span>
                                    @else
                                        <span class="badge badge-warning">Потенциальный</span>
                                    @endif
                                </td>
                                <td>{{ $customer->total_orders }}</td>
                                <td>{{ number_format($customer->total_revenue, 2) }} ₽</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.customers.show', $customer) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Вы уверены?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Пагинация -->
                <div class="d-flex justify-content-center">
                    {{ $customers->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">Клиенты не найдены</h5>
                    <p class="text-gray-400">Добавьте первого клиента, чтобы начать работу</p>
                    <a href="{{ route('customers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Добавить клиента
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

