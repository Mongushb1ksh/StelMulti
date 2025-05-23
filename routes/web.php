<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    HomeController,
    ProductController,
    OrderController,
    ProductionController,
    StockController,
    AdminController,
    ProfileController
};

// Главная
Route::get('/', [HomeController::class, 'index'])->name('home');

// Аутентификация
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Профиль
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Продукция
    Route::resource('products', ProductController::class);
    
    // Заказы
    Route::resource('orders', OrderController::class);
    
    // Производство
    Route::resource('production', ProductionController::class);
    Route::post('/production/{task}/complete', [ProductionController::class, 'complete'])
         ->name('production.complete');
    
    // Склад
    Route::prefix('stock')->group(function () {
        Route::get('/', [StockController::class, 'index'])->name('stock.index');
        Route::get('/materials', [StockController::class, 'materials'])->name('stock.materials');
        Route::post('/materials/add', [StockController::class, 'addMaterial'])->name('stock.materials.add');
        Route::post('/materials/{material}/adjust', [StockController::class, 'adjustStock'])->name('stock.materials.adjust');
    });
    
    // Админка
    Route::middleware('can:admin')->prefix('admin')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::resource('users', AdminController::class)->except(['show']);
        Route::post('/users/{user}/block', [AdminController::class, 'block'])->name('admin.users.block');
        Route::post('/users/{user}/unblock', [AdminController::class, 'unblock'])->name('admin.users.unblock');
        
        // Категории
        Route::resource('categories', CategoryController::class)->except(['show']);
    });
});