<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Notifications\UserRegisteredNotification;


class AuthController extends Controller
{
    public function register(Request $request){
        try{
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:6|confirmed',
                'role_id' => 'nullable|exists:roles,id',
            ],
            
            [
                'password.confirmed' => 'Пароли не совпадают.',
            ]
            
        
            );

            $user = new User();
            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->password = Hash::make($validatedData['password']);
            $user->role_id = 2;
            $user->is_approved = false;
            $user->save();

            $admin = User::where('role_id', 1)->first(); // Находим администратора
            if ($admin) {
                $admin->notify(new UserRegisteredNotification($user));
            }

            return redirect('/login')->with('info', 'Регистрация завершена. Ожидайте подтверждения администратора.');
        } catch (\Exception $e) {
            Log::error('Ошибка при регистрации: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Произошла ошибка при регистрации. Попробуйте снова.']);
        }    
            
    }

    public function login(Request $request)
{
    try {
      
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

       
        $user = User::where('email', $validatedData['email'])->first();

        if ($user && !$user->is_approved) {
            return back()->withErrors(['email' => 'Ваш аккаунт еще не одобрен администратором.']);
        }

       
        if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
            return redirect('/home')->with('success', 'Вы успешно вошли в систему.');
        }

        return back()->withErrors(['email' => 'Неверный email или пароль.']);
    } catch (\Exception $e) {
        Log::error('Ошибка при входе: ' . $e->getMessage());

        return back()->withErrors(['error' => 'Произошла ошибка при входе. Попробуйте снова.']);
    }
}


    public function logout(Request $request)
    {
        try {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect('/login')->with('success', 'Вы вышли из системы');
        } catch (\Exception $e) {
            return redirect('/')->withErrors(['error' => 'Произошла ошибка при выходе. Попробуйте снова.']);
        }
    }
}
