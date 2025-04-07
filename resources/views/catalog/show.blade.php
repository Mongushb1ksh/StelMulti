@extends('layout')

@section('main_content')
<div class="product-details">
    <h2>{{ $product->name }}</h2>

    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">

    <p><strong>Описание:</strong> {{ $product->description }}</p>
    <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
    <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf
        <input type="hidden" name="items[0][product_name]" value="{{ $product->name }}">
        <input type="number" name="items[0][quantity]" placeholder="Количество" min="1" required>
        <input type="hidden" name="items[0][price]" value="{{ $product->price }}">
        <button type="submit" class="btn btn-success">Добавить в заказ</button>
    </form>
</div>



@endsection