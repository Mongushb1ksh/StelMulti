<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionTask;

class MainController extends Controller
{
    public function layout(){
        return view('layout');
    }
    public function home(){
        // Загрузка задач с пагинацией и связанными данными
    $tasks = ProductionTask::with('order', 'materials.material', 'workers.worker')->paginate(10);

    // Передача данных в шаблон
    return view('home', compact('tasks'));
    }
}
