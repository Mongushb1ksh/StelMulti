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