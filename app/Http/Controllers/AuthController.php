<?php

namespace App\Http\Controllers;
 
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(): RedirectResponse
    {
        try {
            User::registerUser(request()->all());
            return redirect('/login')->with('info', 'Регистрация завершена. Ожидайте подтверждения.');
        } catch (\Exception $e) {
            Log::error('Registration error: '.$e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function login(): RedirectResponse
    {
        try {
            User::attemptLogin(request()->all());
            return redirect('/home')->with('success', 'Вы успешно вошли!');
        } catch (\Exception $e) {
            Log::error('Login error: '.$e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout(): RedirectResponse
    {
        try {
            User::logoutUser();
            request()->session()->invalidate();
            request()->session()->regenerateToken();
            return redirect('/login')->with('success', 'Вы вышли из системы');
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'Ошибка при выходе']);
        }
    }
}