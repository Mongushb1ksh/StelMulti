<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileService
{
    public function updateProfile(array $data): void
    {
        $user = Auth::user();
        $user->update($data);
    }

    public function changePassword(string $currentPassword, string $newPassword): void
    {
        $user = Auth::user();

        // Проверяем текущий пароль
        if (!Hash::check($currentPassword, $user->password)) {
            throw new \Exception('Текущий пароль неверный.');
        }

        // Обновляем пароль
        
        $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }
}