@extends('layouts.app')

@section('main_content')
<div class="order-container">
    <h2>Создание заказа</h2>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        
        @if(auth()->user()->role->name === 'Admin')
            <div>
                <label for="user_id">Клиент</label>
                <select name="user_id" id="user_id" required>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>
        @endif

        <div>
            <label>Товары</label>
            <div id="items-container">
                <div class="item-row">
                    <input type="text" name="items[0][product_name]" placeholder="Название товара" required>
                    <input type="number" name="items[0][quantity]" placeholder="Количество" min="1" required>
                    <input type="number" name="items[0][price]" placeholder="Цена" step="0.01" min="0" required>
                </div>
            </div>
            <button type="button" id="add-item">Добавить товар</button>
        </div>

        <button type="submit">Создать заказ</button>
    </form>
</div>

<script>
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', function () {
        const container = document.getElementById('items-container');
        const newRow = document.createElement('div');
        newRow.classList.add('item-row');
        newRow.innerHTML = `
            <input type="text" name="items[${itemIndex}][product_name]" placeholder="Название товара" required>
            <input type="number" name="items[${itemIndex}][quantity]" placeholder="Количество" min="1" required>
            <input type="number" name="items[${itemIndex}][price]" placeholder="Цена" step="0.01" min="0" required>
        `;
        container.appendChild(newRow);
        itemIndex++;
    });
</script>
@endsection