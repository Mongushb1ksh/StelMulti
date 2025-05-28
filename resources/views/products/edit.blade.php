@extends('layouts.app')

@section('title', 'Редактирование товара')

@section('main_content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Редактирование товара</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Название товара</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="{{ old('name', $product->name) }}" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label">Категория</label>
                            <select class="form-select" id="category_id" name="category_id" required>
                                <option value="">Выберите категорию</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Описание</label>
                            <textarea class="form-control" id="description" name="description" 
                                      rows="3">{{ old('description', $product->description) }}</textarea>
                        </div>

                        <div class="form-group mb-3">
                            <label for="quantity" class="form-label">Количество на складе</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" 
                                   value="{{ old('quantity', $product->quantity) }}" min="0" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="unit_price" class="form-label">Цена за единицу</label>
                            <input type="number" step="0.01" class="form-control" id="unit_price" name="unit_price" 
                                   value="{{ old('unit_price', $product->unit_price) }}" min="0" required>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Изображение товара</label>
                            @if($product->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="Current image" style="max-height: 200px;">
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="remove_image" name="remove_image">
                                        <label class="form-check-label" for="remove_image">
                                            Удалить текущее изображение
                                        </label>
                                    </div>
                                </div>
                            @endif
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                id="image" name="image">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Сохранить изменения</button>
                            
                            <button type="button" class="btn btn-danger" 
                                    onclick="if(confirm('Вы уверены, что хотите удалить этот товар?')) {
                                        document.getElementById('delete-form').submit();
                                    }">
                                Удалить товар
                            </button>
                            
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">Назад к списку</a>
                        </div>
                    </form>

                    <form id="delete-form" action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-none">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection