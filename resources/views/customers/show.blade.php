@extends('layouts.app')

@section('main_content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Информация о клиенте</h1>
        <div>
            <a href="{{ route('admin.customers.edit', $customer) }}" class="btn btn-warning">
                <i class="fas fa-edit fa-sm"></i> Редактировать
            </a>
            <a href="{{ route('customers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left fa-sm"></i> Назад к списку
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Основная информация -->
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Основная информация</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>ID:</strong></td>
                                    <td>{{ $customer->id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Имя:</strong></td>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Телефон:</strong></td>
                                    <td>{{ $customer->phone ?? 'Не указан' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Компания:</strong></td>
                                    <td>{{ $customer->company ?? 'Не указана' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>ИНН:</strong></td>
                                    <td>{{ $customer->tax_number ?? 'Не указан' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Статус:</strong></td>
                                    <td>
                                        @if($customer->status === 'active')
                                            <span class="badge badge-success">Активный</span>
                                        @elseif($customer->status === 'inactive')
                                            <span class="badge badge-secondary">Неактивный</span>
                                        @else
                                            <span class="badge badge-warning">Потенциальный</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Дата регистрации:</strong></td>
                                    <td>{{ $customer->created_at->format('d.m.Y H:i') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($customer->address)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>Адрес:</strong></h6>
                                <p>{{ $customer->address }}</p>
                            </div>
                        </div>
                    @endif

                    @if($customer->notes)
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6><strong>Примечания:</strong></h6>
                                <p>{{ $customer->notes }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Статистика -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Статистика</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <h4 class="text-primary">{{ $customer->total_orders }}</h4>
                                <p class="text-muted">Заказов</p>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ number_format($customer->total_revenue, 2) }} ₽</h4>
                            <p class="text-muted">Выручка</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- История заказов -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">История заказов</h6>
        </div>
        <div class="card-body">
            @if($customer->orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>ID заказа</th>
                                <th>Продукт</th>
                                <th>Количество</th>
                                <th>Статус</th>
                                <th>Дата создания</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->orders as $order)
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->product->name ?? 'N/A' }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>
                                    <span class="badge badge-{{ $order->status === 'completed' ? 'success' : ($order->status === 'pending' ? 'warning' : 'info') }}">
                                        {{ $order->getStatusText() }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('orders.show', $order) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-shopping-cart fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">Заказы не найдены</h5>
                    <p class="text-gray-400">У этого клиента пока нет заказов</p>
                </div>
            @endif
        </div>
    </div>

    <!-- История счетов -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">История счетов</h6>
        </div>
        <div class="card-body">
            @if($customer->invoices->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Номер счета</th>
                                <th>Сумма</th>
                                <th>Статус</th>
                                <th>Дата выписки</th>
                                <th>Дата оплаты</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                <td>{{ number_format($invoice->total_amount, 2) }} ₽</td>
                                <td>
                                    <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                        {{ $invoice->status_text }}
                                    </span>
                                </td>
                                <td>{{ $invoice->issue_date->format('d.m.Y') }}</td>
                                <td>{{ $invoice->paid_date ? $invoice->paid_date->format('d.m.Y') : 'Не оплачен' }}</td>
                                <td>
                                    <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-file-invoice fa-3x text-gray-300 mb-3"></i>
                    <h5 class="text-gray-500">Счета не найдены</h5>
                    <p class="text-gray-400">У этого клиента пока нет счетов</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

