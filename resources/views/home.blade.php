@extends('layout')



@section('header_content')

<div class="user-info">
    Добро пожаловать, {{ auth()->user()->name }}
</div>


@endsection

@section('main_content')


        <div class="dashboard-card">
            <h2>Обзор продаж</h2>
            <p>За последний месяц: 500 заказов на сумму 2 500 000 ₽</p>
            <div class="chart"></div>
        </div>

        <div class="dashboard-card">
            <h2>Статус производства</h2>
            <p>В работе: 20 заказов, Завершено: 30 заказов</p>
            <div class="chart"></div>
        </div>

        <div class="dashboard-card">
            <h2>Финансовые показатели</h2>
            <p>Доход: 3 000 000 ₽, Расходы: 1 800 000 ₽</p>
            <div class="chart"></div>
        </div>


   
@endsection