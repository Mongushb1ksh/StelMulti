<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;


class User extends Authenticatable
{
    protected $fillable = ['name', 'email', 'password', 'role_id', 'is_approved'];
    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['password' => 'hashed'];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public static function validateRegistration(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'nullable|exists:roles,id',
        ], [
            'password.confirmed' => 'Пароли не совпадают.'
        ]);

        return $validator->validate();
    }

    public static function validateLogin(array $data): array
    {
        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        return $validator->validate();
    }

    public static function registerUser(array $data): self
    {
        try {
            $validated = self::validateRegistration($data);
            
            return self::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'] ?? null,
                'is_approved' => false
            ]);
        } catch (\Exception $e) {
            Log::error('Registration error: '.$e->getMessage());
            throw $e;
        }
    }

    public static function attemptLogin(array $credentials): void
    {
        $validated = self::validateLogin($credentials);

        if (!Auth::attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed')
            ]);
        }

        if (!Auth::user()->is_approved) {
            Auth::logout();
            throw new \Exception('Ваш аккаунт ожидает подтверждения');
        }
    }

    public static function logoutUser(): void
    {
        try {
            Auth::logout();
        } catch (\Exception $e) {
            Log::error('Logout error: '.$e->getMessage());
            throw $e;
        }
    }
}
