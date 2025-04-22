<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class AdminUserService
{
    public function updateUser(User $user, array $data): void
    {
        // Обновляем данные пользователя
        $user->update($data);
    }

    public function approveUser(User $user): void
    {
        // Одобряем пользователя
        $user->update(['is_approved' => true]);
    }
}