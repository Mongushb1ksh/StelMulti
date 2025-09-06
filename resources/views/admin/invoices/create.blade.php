@extends('layouts.app')
@section('title', 'Создание счета - Администрирование')
@section('main_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Создание нового счета</h1>
        <a href="{{ route('admin.invoices.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Назад к списку
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h5 class="mb-0">Информация о счете</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.invoices.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_id" class="form-label">Клиент *</label>
                                <select name="customer_id" id="customer_id" class="form-select" required>
                                    <option value="">Выберите клиента</option>
                                    @foreach($customers as $customer)
                                        <option value="{{ $customer->id }}" {{ old('customer_id') == $customer->id ? 'selected' : '' }}>
                                            {{ $customer->name }} ({{ $customer->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="order_id" class="form-label">Заказ (необязательно)</label>
                                <select name="order_id" id="order_id" class="form-select">
                                    <option value="">Без заказа</option>
                                    @foreach($orders as $order)
                                        <option value="{{ $order->id }}" {{ old('order_id') == $order->id ? 'selected' : '' }}>
                                            Заказ №{{ $order->id }} - {{ $order->client_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="subtotal" class="form-label">Сумма без НДС *</label>
                                <input type="number" step="0.01" name="subtotal" id="subtotal" class="form-control" 
                                       value="{{ old('subtotal') }}" required>
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="tax_amount" class="form-label">НДС</label>
                                <input type="number" step="0.01" name="tax_amount" id="tax_amount" class="form-control" 
                                       value="{{ old('tax_amount', 0) }}">
                            </div>
                            
                            <div class="col-md-4 mb-3">
                                <label for="total_amount" class="form-label">Общая сумма *</label>
                                <input type="number" step="0.01" name="total_amount" id="total_amount" class="form-control" 
                                       value="{{ old('total_amount') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="issue_date" class="form-label">Дата выставления *</label>
                                <input type="date" name="issue_date" id="issue_date" class="form-control" 
                                       value="{{ old('issue_date', date('Y-m-d')) }}" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="due_date" class="form-label">Срок оплаты *</label>
                                <input type="date" name="due_date" id="due_date" class="form-control" 
                                       value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Статус</label>
                            <select name="status" id="status" class="form-select">
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Черновик</option>
                                <option value="sent" {{ old('status') == 'sent' ? 'selected' : '' }}>Отправлен</option>
                                <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Оплачен</option>
                                <option value="overdue" {{ old('status') == 'overdue' ? 'selected' : '' }}>Просрочен</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Отменен</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Примечания</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Создать счет
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const subtotalInput = document.getElementById('subtotal');
    const taxInput = document.getElementById('tax_amount');
    const totalInput = document.getElementById('total_amount');

    function calculateTotal() {
        const subtotal = parseFloat(subtotalInput.value) || 0;
        const tax = parseFloat(taxInput.value) || 0;
        const total = subtotal + tax;
        totalInput.value = total.toFixed(2);
    }

    subtotalInput.addEventListener('input', calculateTotal);
    taxInput.addEventListener('input', calculateTotal);
    
    // Автоматический расчет НДС (20%)
    subtotalInput.addEventListener('blur', function() {
        const subtotal = parseFloat(this.value) || 0;
        const tax = subtotal * 0.20;
        taxInput.value = tax.toFixed(2);
        calculateTotal();
    });
});
</script>
@endsection
@endsection
