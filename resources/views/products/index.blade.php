@extends('layouts.app')

@section('title', 'Управление продукцией')

@section('main_content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
    @if(!auth()->check())
        <h1>Главная</h1>
    @endif
        @if(optional(auth()->user())->role?->name === 'Warehouse Manager')
        <h1>Управление продукцией</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Добавить товар
        </a>
        @endif
    </div>
    <form method="GET" action="{{ route('products.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-auto">
                <label for="category_id" class="form-label">Категория</label>
                <select name="category_id" id="category_id" class="form-select">
                    <option value="">Все</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <label for="name" class="form-label">Название товара</label>
                <input type="text" name="name" id="name" class="form-control" 
                    value="{{ request('name') }}" placeholder="Поиск по названию">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Применить</button>
                <a href="{{ route('products.index') }}" class="btn btn-secondary">Очистить</a>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Название</th>
                        <th>Категория</th>
                        @if(auth()->check())
                        <th>Количество</th>
                        @endif
                        <th>Цена</th>
                        <th>Действия</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        @if(auth()->check())
                        <td>{{ $product->quantity }} шт.</td>
                        @endif
                        <td>{{ number_format($product->unit_price, 2) }} ₽</td>
                        <td class="table-action">
                            <a href="{{ route('products.show', $product) }}" 
                               class="btn btn-sm btn-primary">Просмотр</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection