@extends('layout')

@section('main_content')
<div class="production-container">
    <h2>Создание производственного задания</h2>

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

    <form action="{{ route('production.store') }}" method="POST">
        @csrf

        <!-- Выбор заказа -->
        <div class="form-group">
            <label for="order_id">Заказ:</label>
            <select name="order_id" id="order_id" class="form-control" required>
                <option value="">Выберите заказ</option>
                @foreach($orders as $order)
                    <option value="{{ $order->id }}">Заказ #{{ $order->id }}</option>
                @endforeach
            </select>
        </div>

        <!-- Материалы -->
        <div class="form-group">
            <label>Материалы:</label>
            <div id="materials-container">
                @for ($i = 0; $i < 5; $i++) <!-- Фиксированное количество полей -->
                    <div class="material-row form-inline mb-2">
                        <select name="materials[{{ $i }}][id]" class="form-control mr-2" >
                            <option value="">Выберите материал</option>
                            @foreach($materials as $material)
                                <option value="{{ $material->id }}">{{ $material->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="materials[{{ $i }}][quantity_required]" class="form-control" placeholder="Необходимое количество" min="1" >
                    </div>
                @endfor
            </div>
        </div>


        <!-- Кнопка отправки -->
        <button type="submit" class="btn btn-primary">Создать задание</button>
    </form>
</div>
@endsection