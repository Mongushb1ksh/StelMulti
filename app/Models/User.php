<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_blocked',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    public function isAdmin()
    {
        return $this->role_id === 1;
    }

    public function isApproved()
    {
        return !$this->is_blocked;
    }

 
    public static function registerUser(array $data): self
    {
        $validated = self::validateRegistrationData($data);
        
        return self::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => 5,
            'is_blocked' => true,
        ]);
    }

    public static function attemptLogin(array $credentials): bool
    {
        $user = self::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return false;
        }

        if ($user->is_blocked) {
            throw new \Exception('Ваш аккаунт ожидает подтверждения администратором');
        }

        Auth::login($user);
        return true;
    }

    public static function approveUser(int $userId): void
    {
        $user = self::findOrFail($userId);
        $user->update(['is_blocked' => false]);
    }

    public static function blockUser(int $userId): void
    {
        $user = self::findOrFail($userId);
        $user->update(['is_blocked' => true]);
    }

    public static function validateRegistrationData(array $data): array
    {
        $validator = \Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function validateUpdateData(array $data, int $userId): array
    {
        $validator = \Validator::make($data, [
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => 'sometimes|string|min:8|confirmed',
            'role_id' => 'sometimes|exists:roles,id',
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }

        return $validator->validated();
    }

    public static function createUser(array $data): self
    {
        $validated = self::validateUpdateData($data, 0);
        
        return self::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role_id' => $validated['role_id'],
            'is_blocked' => false, // Админ создает сразу активных пользователей
        ]);
    }

    public static function updateUser(array $data, int $userId): self
    {
        $user = self::findOrFail($userId);
        $validated = self::validateUpdateData($data, $userId);
        
        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];
        
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }
        
        $user->save();
        
        return $user;
    }

    public static function deleteUser(int $userId): void
    {
        $user = self::findOrFail($userId);
        $user->delete();
    }
}