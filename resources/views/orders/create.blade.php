@extends('layouts.app')

@section('main_content')
<div class="order-container">
    <h2>Создание заказа</h2>
    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <div>
            <label for="client_name" class="form-label">Имя клиента</label>
            <input type="text" class="form-control" id="client_name" name="client_name" required>
            <label for="client_email" class="form-label">Электронная почта клиента</label>
            <input type="text" class="form-control" id="client_email" name="client_email" required>
        </div>
        <div id="items-container">
            <button type="button" id="add-item">Добавить товар</button>
            <label for="product_id" class="form-label">Товар</label>
            <select class="form-select" id="product_id" name="product_id" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <div>
                <label for="quantity" class="form-label">Количество</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>
        </div>

        <div>
            <label for="status" class="form-label">Статус</label>
            <select class="form-select" id="status" name="status" required>
                @foreach((new App\Models\Order)::$statuses as $key => $status)
                    <option value="{{ $key }}">{{ $status }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit">Создать заказ</button>
        <a href="{{ route('orders.index') }}" class="btn btn-primary">Отмена</a>
    </form>
</div>
<script>
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', function () {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.classList.add('item-row');
        newRow.innerHTML = `
            <select class="form-select" id="product_id" name="product_id" required>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
            <div>
                <label for="quantity" class="form-label">Количество</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
            </div>
        `;
        container.appendChild(newRow);
        itemIndex++;
    });
</script>

@endsection