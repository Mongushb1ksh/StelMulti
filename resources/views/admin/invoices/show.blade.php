@extends('layouts.app')
@section('title', 'Просмотр счета - Администрирование')
@section('main_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Счет {{ $invoice->invoice_number }}</h1>
        <div>
            <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Редактировать
            </a>
            <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn btn-secondary" target="_blank">
                <i class="fas fa-file-pdf"></i> Скачать PDF
            </a>
            <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Назад к списку
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Основная информация о счете -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Информация о счете</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Номер счета:</strong></td>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Статус:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : ($invoice->status === 'sent' ? 'warning' : 'secondary')) }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Дата выставления:</strong></td>
                                    <td>{{ $invoice->issue_date->format('d.m.Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Срок оплаты:</strong></td>
                                    <td>{{ $invoice->due_date->format('d.m.Y') }}</td>
                                </tr>
                                @if($invoice->paid_date)
                                <tr>
                                    <td><strong>Дата оплаты:</strong></td>
                                    <td>{{ $invoice->paid_date->format('d.m.Y') }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Сумма без НДС:</strong></td>
                                    <td>{{ number_format($invoice->subtotal, 2) }} ₽</td>
                                </tr>
                                <tr>
                                    <td><strong>НДС:</strong></td>
                                    <td>{{ number_format($invoice->tax_amount, 2) }} ₽</td>
                                </tr>
                                <tr>
                                    <td><strong>Общая сумма:</strong></td>
                                    <td><strong>{{ number_format($invoice->total_amount, 2) }} ₽</strong></td>
                                </tr>
                                @if($invoice->due_date < now() && $invoice->status !== 'paid')
                                <tr>
                                    <td><strong>Дней просрочки:</strong></td>
                                    <td class="text-danger">{{ $invoice->due_date->diffInDays(now()) }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Информация о клиенте -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Информация о клиенте</h5>
                </div>
                <div class="card-body">
                    @if($invoice->customer)
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Имя:</strong></td>
                                    <td>{{ $invoice->customer->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $invoice->customer->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Телефон:</strong></td>
                                    <td>{{ $invoice->customer->phone ?? 'Не указан' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Компания:</strong></td>
                                    <td>{{ $invoice->customer->company ?? 'Не указана' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>ИНН:</strong></td>
                                    <td>{{ $invoice->customer->tax_number ?? 'Не указан' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Статус:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->customer->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($invoice->customer->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @else
                    <div class="alert alert-warning">
                        Клиент не найден
                    </div>
                    @endif
                </div>
            </div>

            <!-- Связанный заказ -->
            @if($invoice->order)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Связанный заказ</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Номер заказа:</strong></td>
                                    <td>
                                        <a href="{{ route('orders.show', $invoice->order) }}">
                                            Заказ №{{ $invoice->order->id }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Клиент:</strong></td>
                                    <td>{{ $invoice->order->client_name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Товар:</strong></td>
                                    <td>{{ $invoice->order->product->name ?? 'Не указан' }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Количество:</strong></td>
                                    <td>{{ $invoice->order->quantity }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Статус заказа:</strong></td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->order->status === 'completed' ? 'success' : ($invoice->order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ $invoice->order->getStatusText() }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Сумма заказа:</strong></td>
                                    <td>{{ number_format($invoice->order->total_amount ?? 0, 2) }} ₽</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Примечания -->
            @if($invoice->notes)
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Примечания</h5>
                </div>
                <div class="card-body">
                    <p>{{ $invoice->notes }}</p>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Действия -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Действия</h5>
                </div>
                <div class="card-body">
                    @if($invoice->status === 'sent')
                    <form action="{{ route('admin.invoices.mark-paid', $invoice) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Отметить как оплаченный?')">
                            <i class="fas fa-check"></i> Отметить как оплаченный
                        </button>
                    </form>
                    @endif

                    @if($invoice->status === 'draft')
                    <form action="{{ route('admin.invoices.update', $invoice) }}" method="POST" class="mb-3">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="sent">
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="fas fa-paper-plane"></i> Отправить клиенту
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn btn-info w-100 mb-3" target="_blank">
                        <i class="fas fa-file-pdf"></i> Скачать PDF
                    </a>

                    <a href="{{ route('admin.invoices.edit', $invoice) }}" class="btn btn-warning w-100 mb-3">
                        <i class="fas fa-edit"></i> Редактировать
                    </a>

                    <form action="{{ route('admin.invoices.destroy', $invoice) }}" method="POST" 
                          onsubmit="return confirm('Вы уверены, что хотите удалить этот счет?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-trash"></i> Удалить
                        </button>
                    </form>
                </div>
            </div>

            <!-- История изменений -->
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">История</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6>Счет создан</h6>
                                <p class="text-muted">{{ $invoice->created_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                        
                        @if($invoice->status !== 'draft')
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6>Статус изменен на "{{ ucfirst($invoice->status) }}"</h6>
                                <p class="text-muted">{{ $invoice->updated_at->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($invoice->paid_date)
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6>Оплачен</h6>
                                <p class="text-muted">{{ $invoice->paid_date->format('d.m.Y H:i') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -35px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}

.timeline-content h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-content p {
    margin-bottom: 0;
    font-size: 0.875rem;
}
</style>
@endsection
@endsection
