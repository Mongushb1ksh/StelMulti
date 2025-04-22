<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function login(array $credentials): bool
    {
        $user = User::where('email', $credentials['email'])->first();

        // Проверяем, одобрен ли пользователь
        if ($user && !$user->is_approved) {
            throw new \Exception('Ваш аккаунт еще не одобрен администратором.');
        }

        // Пытаемся выполнить вход
        if (!Auth::attempt($credentials)) {
            throw new \Exception('Неверный email или пароль.');
        }

        return true;
    }

    public function logout(): void
    {
        Auth::logout();
    }
}