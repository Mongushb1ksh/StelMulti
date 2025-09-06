@extends('layouts.app')
@section('title', 'Отчеты и аналитика')
@section('main_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Отчеты и аналитика</h1>
        <div>
            <a href="{{ route('reports.sales') }}" class="btn btn-primary">
                <i class="fas fa-chart-line"></i> Продажи
            </a>
            <a href="{{ route('reports.financial') }}" class="btn btn-success">
                <i class="fas fa-dollar-sign"></i> Финансы
            </a>
            <a href="{{ route('reports.inventory') }}" class="btn btn-info">
                <i class="fas fa-boxes"></i> Склад
            </a>
            <a href="{{ route('reports.production') }}" class="btn btn-warning">
                <i class="fas fa-cogs"></i> Производство
            </a>
        </div>
    </div>

    <!-- Ключевые показатели -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row g-0 align-items-center">
                        <div class="col me-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Общая выручка
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total_revenue'] ?? 0, 2) }} ₽
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Заказы (месяц)
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['monthly_orders'] ?? 0 }}
                                </div>
                            </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Активные клиенты
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $stats['active_customers'] ?? 0 }}
                                </div>
                            </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                        <div class="row g-0 align-items-center">
                            <div class="col me-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Просроченные счета
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ number_format($stats['overdue_invoices'] ?? 0, 2) }} ₽
                                </div>
                            </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- График продаж -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Динамика продаж</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <div class="dropdown-header">Период:</div>
                            <a class="dropdown-item" href="#" onclick="updateChart('week')">Неделя</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('month')">Месяц</a>
                            <a class="dropdown-item" href="#" onclick="updateChart('year')">Год</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-area">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Круговая диаграмма статусов заказов -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Статусы заказов</h6>
                </div>
                <div class="card-body">
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="ordersStatusChart"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        <span class="me-2">
                            <i class="fas fa-circle text-primary"></i> В ожидании
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-success"></i> Завершены
                        </span>
                        <span class="me-2">
                            <i class="fas fa-circle text-info"></i> В работе
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Таблицы с данными -->
    <div class="row">
        <!-- Последние заказы -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Последние заказы</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Клиент</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        <a href="{{ route('orders.show', $order) }}">{{ $order->id }}</a>
                                    </td>
                                    <td>{{ $order->client_name }}</td>
                                    <td>{{ number_format($order->total_amount ?? 0, 2) }} ₽</td>
                                    <td>
                                        <span class="badge bg-{{ $order->status === 'completed' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                            {{ $order->getStatusText() }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Заказы не найдены</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Последние счета -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Последние счета</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>№</th>
                                    <th>Клиент</th>
                                    <th>Сумма</th>
                                    <th>Статус</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentInvoices as $invoice)
                                <tr>
                                    <td>
                                        <a href="{{ route('admin.invoices.show', $invoice) }}">{{ $invoice->invoice_number }}</a>
                                    </td>
                                    <td>{{ $invoice->customer->name ?? 'Не указан' }}</td>
                                    <td>{{ number_format($invoice->total_amount, 2) }} ₽</td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Счета не найдены</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// График продаж
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesChart = new Chart(salesCtx, {
    type: 'line',
    data: {
        labels: ['Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн'],
        datasets: [{
            label: 'Выручка',
            data: [12000, 19000, 15000, 25000, 22000, 30000],
            borderColor: 'rgb(75, 192, 192)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

// Круговая диаграмма статусов заказов
const ordersCtx = document.getElementById('ordersStatusChart').getContext('2d');
const ordersChart = new Chart(ordersCtx, {
    type: 'doughnut',
    data: {
        labels: ['В ожидании', 'Завершены', 'В работе'],
        datasets: [{
            data: [30, 50, 20],
            backgroundColor: [
                'rgb(54, 162, 235)',
                'rgb(75, 192, 192)',
                'rgb(255, 205, 86)'
            ]
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false
    }
});

function updateChart(period) {
    // Здесь можно добавить логику обновления графика в зависимости от периода
    console.log('Обновление графика для периода:', period);
}
</script>
@endsection
@endsection
