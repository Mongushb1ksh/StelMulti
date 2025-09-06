@extends('layouts.app')
@section('title', 'Счета - Администрирование')
@section('main_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Управление счетами</h1>
        <a href="{{ route('admin.invoices.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Создать счет
        </a>
    </div>

    <!-- Фильтры -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.invoices.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Статус</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Все статусы</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Черновик</option>
                        <option value="sent" {{ request('status') == 'sent' ? 'selected' : '' }}>Отправлен</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Оплачен</option>
                        <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Просрочен</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="customer" class="form-label">Клиент</label>
                    <input type="text" name="customer" id="customer" class="form-control" 
                           value="{{ request('customer') }}" placeholder="Поиск по клиенту">
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Дата с</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" 
                           value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Дата по</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" 
                           value="{{ request('date_to') }}">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Применить фильтры
                    </button>
                    <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Очистить
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Таблица счетов -->
    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>№ Счета</th>
                            <th>Клиент</th>
                            <th>Заказ</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th>Дата создания</th>
                            <th>Срок оплаты</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invoices as $invoice)
                        <tr>
                            <td>
                                <a href="{{ route('admin.invoices.show', $invoice) }}" class="text-decoration-none">
                                    {{ $invoice->invoice_number }}
                                </a>
                            </td>
                            <td>{{ $invoice->customer->name ?? 'Не указан' }}</td>
                            <td>
                                @if($invoice->order)
                                    <a href="{{ route('orders.show', $invoice->order) }}">
                                        Заказ №{{ $invoice->order->id }}
                                    </a>
                                @else
                                    Без заказа
                                @endif
                            </td>
                            <td>{{ number_format($invoice->total_amount, 2) }} ₽</td>
                            <td>
                                <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : ($invoice->status === 'sent' ? 'warning' : 'secondary')) }}">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td>{{ $invoice->issue_date->format('d.m.Y') }}</td>
                            <td>{{ $invoice->due_date->format('d.m.Y') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.invoices.show', $invoice) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($invoice->status === 'sent')
                                    <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Отметить как оплаченный?')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn btn-secondary btn-sm" target="_blank">
                                        <i class="fas fa-file-pdf"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">Счета не найдены</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($invoices->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $invoices->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
