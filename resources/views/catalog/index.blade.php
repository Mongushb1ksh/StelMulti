@extends('layout')

@section('main_content')
<div class="catalog-container">
    <h2>Каталог продукции</h2>

    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
                <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p>
                <a href="{{ route('catalog.show', $product) }}" class="btn btn-primary">Подробнее</a>
            </div>
        @endforeach
    </div><br>
    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
                <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p>
                <a href="{{ route('catalog.show', $product) }}" class="btn btn-primary">Подробнее</a>
            </div>
        @endforeach
    </div><br>
    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
                <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
                <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p>
                <a href="{{ route('catalog.show', $product) }}" class="btn btn-primary">Подробнее</a>
            </div>
        @endforeach
    </div>

    {{ $products->links() }}
</div>
@endsection