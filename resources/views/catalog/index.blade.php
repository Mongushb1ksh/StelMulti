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
                    <button class="btn btn-primary" data-product='@json($product)'>Добавить в заказ</button>
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
                    <button class="btn btn-primary" data-product='@json($product)'>Добавить в заказ</button>
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
                    <button class="btn btn-primary" data-product='@json($product)'>Добавить в заказ</button>
                </div>    
         </div>
        @endforeach
    </div>

    {{ $products->links() }}
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        const orderForm = document.getElementById('order-form');
        const selectedItemsContainer = document.getElementById('selected-items-container');
        let itemIndex = 0;

        // Обработчик для кнопок "Добавить в заказ"
        document.querySelectorAll('.add-to-order').forEach(button => {
            button.addEventListener('click', function () {
                const product = JSON.parse(this.getAttribute('data-product'));

                // Создаем новый элемент для товара
                const itemDiv = document.createElement('div');
                itemDiv.className = 'selected-item';
                itemDiv.innerHTML = `
                    <input type="hidden" name="items[${itemIndex}][product_name]" value="${product.name}">
                    <input type="hidden" name="items[${itemIndex}][price]" value="${product.price}">
                    <div>
                        <strong>${product.name}</strong> - ${product.price} руб.
                        <input type="number" name="items[${itemIndex}][quantity]" placeholder="Количество" min="1" required>
                        <button type="button" class="remove-item">Удалить</button>
                    </div>
                `;

                // Добавляем элемент в контейнер
                selectedItemsContainer.appendChild(itemDiv);

                // Показываем форму, если она скрыта
                orderForm.style.display = 'block';

                // Увеличиваем индекс для следующего товара
                itemIndex++;

                // Обработчик для кнопки "Удалить"
                itemDiv.querySelector('.remove-item').addEventListener('click', function () {
                    selectedItemsContainer.removeChild(itemDiv);
                    if (selectedItemsContainer.children.length === 0) {
                        orderForm.style.display = 'none'; // Скрываем форму, если нет товаров
                    }
                });
            });
        });
    });
</script>

@endsection