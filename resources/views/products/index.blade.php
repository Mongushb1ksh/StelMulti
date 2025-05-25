@extends('layouts.app')

@section('title', 'Управление продукцией')

@section('main_content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Управление продукцией</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Добавить товар
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название</th>
                        <th>Категория</th>
                        <th>Количество</th>
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
                        <td>{{ $product->quantity }} шт.</td>
                        <td>{{ number_format($product->unit_price, 2) }} ₽</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" 
                               class="btn btn-sm btn-primary">Просмотр</a>
                            <a href="{{ route('products.edit', $product) }}" 
                               class="btn btn-sm btn-primary">Редактировать</a>
                            <form action="{{ route('products.destroy', $product) }}" 
                                  method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger"
                                        onclick="return confirm('Удалить этот товар?')">
                                    Удалить
                                </button>
                            </form>
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