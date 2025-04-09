<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockController;
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


Route::middleware('auth')->group(function() {
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password');
});

Route::middleware('auth')->group(function() {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/approved', [UserController::class, 'approved'])->name('admin.users.approved');
    Route::put('/admin/users/{user}/edit', [UserController::class, 'update'])->name('admin.users.update');
    Route::get('/admin/users/{user}', [UserController::class, 'edit'])->name('admin.users.edit');
});


Route::middleware('auth')->prefix('orders')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store');
    Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
});


Route::middleware('auth')->group(function () {
    Route::get('/catalog', [ProductController::class, 'index'])->name('catalog.index');
    Route::get('/catalog/{product}', [ProductController::class, 'show'])->name('catalog.show');
});


Route::middleware('auth')->prefix('stock')->group(function () {
    Route::get('/', [StockController::class, 'index'])->name('stock.index');
    Route::post('/{product}/receipt', [StockController::class, 'receipt'])->name('stock.receipt');
    Route::post('/{product}/consumption', [StockController::class, 'consumption'])->name('stock.consumption');
    Route::post('/{product}/transfer', [StockController::class, 'transfer'])->name('stock.transfer');
});