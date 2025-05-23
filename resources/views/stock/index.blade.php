@extends('layouts.app')

@section('title', 'Управление складом')

@section('main_content')
<div class="container">
    <h1 class="mb-4">Управление складом</h1>

    <ul class="nav nav-tabs mb-4">
        <li class="nav-item">
            <a class="nav-link active" href="{{ route('stock.index') }}">Товары</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('stock.materials') }}">Материалы</a>
        </li>
    </ul>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>Товары на складе</span>
            <a href="{{ route('products.create') }}" class="btn btn-sm btn-primary">
                Добавить товар
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Количество</th>
                            <th>Цена</th>
                            <th>Статус</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td class="{{ $product->quantity < 10 ? 'text-danger fw-bold' : '' }}">
                                {{ $product->quantity }} шт.
                            </td>
                            <td>{{ number_format($product->unit_price, 2) }} ₽</td>
                            <td>
                                @if($product->quantity < 5)
                                <span class="badge bg-danger">Критический уровень</span>
                                @elseif($product->quantity < 10)
                                <span class="badge bg-warning">Низкий уровень</span>
                                @else
                                <span class="badge bg-success">В наличии</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection