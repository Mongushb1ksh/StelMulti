@extends('layout')

@section('main_content')
<div class="stock-container">
    <h2>Управление складом</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Название</th>
                <th>Описание</th>
                <th>Цена</th>
                <th>Количество</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->description }}</td>
                    <td>{{ $product->price }} руб.</td>
                    <td>{{ $product->quantity }}</td>
                    <td>
                        <form action="{{ route('stock.receipt', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="number" name="quantity" placeholder="Количество" min="1" required>
                            <button type="submit" class="btn btn-primary">Приход</button>
                        </form>

                        <form action="{{ route('stock.consumption', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="number" name="quantity" placeholder="Количество" min="1" required>
                            <button type="submit" class="btn btn-primary">Расход</button>
                        </form>

                        <form action="{{ route('stock.transfer', $product) }}" method="POST" style="display:inline;">
                            @csrf
                            <input type="number" name="quantity" placeholder="Количество" min="1" required>
                            <select  name="to_product_id" required>
                                <option value="">Выберите товар</option>
                                @foreach($products as $otherProduct)
                                    @if($otherProduct->id !== $product->id)
                                        <option value="{{ $otherProduct->id }}">{{ $otherProduct->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button type="submit" class="btn btn-primary">Перемещение</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection