<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;


Route::get('/', [MainController::class, 'layout']);
Route::get('/home', [MainController::class, 'home'])->middleware('auth');


Route::get('/register', function () {
    return view('auth.register');
});

Route::get('/login', function () {
    return view('auth.login');
});


Route::post('/login/show', [AuthController::class, 'login']);
Route::post('/register/show', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
