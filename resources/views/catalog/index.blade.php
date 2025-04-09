@extends('layout')

@section('main_content')
<div class="catalog-container">
    <h2>Каталог продукции</h2>


    <form id="order-form" action="{{ route('orders.store') }}" method="POST" style="display: none;">
        @csrf
        <h3>Текущий заказ</h3>
        <div id="selected-items-container">

        </div>
        <button type="submit" class="btn btn-success">Создать заказ</button>
    </form>

    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                <div>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
                    <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p> 
                    <a href="{{ route('catalog.show', $product) }}" class="btn btn-primary">Подробнее</a>
                </div>    
                
            </div>
        @endforeach
    </div><br>
    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                 <div>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
                    <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p> 
                    <a href="{{ route('catalog.show', $product) }}" class="btn btn-primary">Подробнее</a>
                </div>    
           </div>
        @endforeach
    </div><br>
    <div class="products-grid">
        @foreach($products as $product)
            <div class="product-card">
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                <div>
                    <h3>{{ $product->name }}</h3>
                    <p>{{ $product->description }}</p>
                    <p><strong>Цена:</strong> {{ $product->price }} руб.</p>
                    <p><strong>В наличии:</strong> {{ $product->quantity }} шт.</p> 
                    <a href="{{ route('catalog.show', $product) }}" class="btn btn-primary">Подробнее</a>
                </div>    
         </div>
        @endforeach
    </div>

    {{ $products->links() }}
</div>




@endsection