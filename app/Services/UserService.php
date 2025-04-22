<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Notifications\UserRegisteredNotification;

class UserService
{
    public function registerUser(array $data): User
    {
        // Создаем нового пользователя
        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role_id = $data['role_id'] ?? 2; // По умолчанию роль пользователя
        $user->is_approved = false; // Пользователь не одобрен
        $user->save();

        // Уведомляем администратора
        $admin = User::where('role_id', 1)->first();
        if ($admin) {
            $admin->notify(new UserRegisteredNotification($user));
        }

        return $user;
    }
}