<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $userService;
    protected $authService;

    public function __construct(UserService $userService, AuthService $authService)
    {
        $this->userService = $userService;
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role_id' => 'nullable|exists:roles,id',
            ], [
                'password.confirmed' => 'Пароли не совпадают.',
            ]);

            $this->userService->registerUser($validatedData);

            return redirect('/login')->with('info', 'Регистрация завершена. Ожидайте подтверждения администратора.');
        } catch (\Exception $e) {
            Log::error('Ошибка при регистрации: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function login(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'password' => 'required|string|min:6',
            ]);

            $this->authService->login($validatedData);

            return redirect('/home')->with('success', 'Вы успешно вошли в систему.');
        } catch (\Exception $e) {
            Log::error('Ошибка при входе: ' . $e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout(Request $request)
    {
        try {
            $this->authService->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Вы вышли из системы');
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'Произошла ошибка при выходе. Попробуйте снова.']);
        }
    }
}