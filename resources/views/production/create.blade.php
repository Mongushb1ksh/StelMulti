@extends('layout')

@section('main_content')
<div class="production-container">
    <h2>Создание производственного задания</h2>

    <form action="{{ route('production.store') }}" method="POST">
        @csrf
        <label for="order_id">Заказ:</label>
        <select name="order_id" id="order_id" required>
            <option value="">Выберите заказ</option>
            @foreach($orders as $order)
                <option value="{{ $order->id }}">Заказ #{{ $order->id }}</option>
            @endforeach
        </select>

        <label>Материалы:</label>
        <div id="materials-container">
            <div class="material-row">
                <select name="materials[0][id]" required>
                    <option value="">Выберите материал</option>
                    @foreach($materials as $material)
                        <option value="{{ $material->id }}">{{ $material->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="materials[0][quantity_required]" placeholder="Необходимое количество" min="1" required>
            </div>
        </div>
        <button type="button" id="add-material">Добавить материал</button>

        <label>Сотрудники:</label>
        <div id="workers-container">
            @foreach($workers as $worker)
                <div>
                    <input type="checkbox" name="workers[]" value="{{ $worker->id }}" id="worker-{{ $worker->id }}">
                    <label for="worker-{{ $worker->id }}">{{ $worker->name }}</label>
                </div>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary">Создать задание</button>
    </form>
</div>

<script>
    let materialIndex = 1;

    document.getElementById('add-material').addEventListener('click', function () {
        const container = document.getElementById('materials-container');
        const newRow = document.createElement('div');
        newRow.classList.add('material-row');
        newRow.innerHTML = `
            <select name="materials[${materialIndex}][id]" required>
                <option value="">Выберите материал</option>
                @foreach($materials as $material)
                    <option value="{{ $material->id }}">{{ $material->name }}</option>
                @endforeach
            </select>
            <input type="number" name="materials[${materialIndex}][quantity_required]" placeholder="Необходимое количество" min="1" required>
        `;
        container.appendChild(newRow);
        materialIndex++;
    });
</script>
@endsection