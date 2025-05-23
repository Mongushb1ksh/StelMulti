@extends('layout')

@section('main_content')
<div class="product-details">
    <h2>{{ $product->name }}</h2>

    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}">

    <p><strong>Описание:</strong> {{ $product->description }}</p>
    <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
    <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p>
    <a href="/catalog" class="btn btn-primary">Назад</a>
</div>



@endsection