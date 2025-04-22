<?php

namespace App\Http\Controllers;

use App\Services\AdminUserService;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $adminUserService;

    public function __construct(AdminUserService $adminUserService)
    {
        $this->adminUserService = $adminUserService;
    }

    public function index()
    {
        $users = User::with('role')->paginate(10); // Загружаем связь с ролью
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'nullable|exists:roles,id',
        ]);

        try {
            $this->adminUserService->updateUser($user, $validatedData);

            return redirect()->route('admin.users.index')->with('success', 'Данные пользователя успешно обновлены.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при обновлении данных пользователя.']);
        }
    }

    public function approved(User $user)
    {
        try {
            $this->adminUserService->approveUser($user);

            return redirect()->route('admin.users.index')->with('success', 'Пользователь успешно одобрен.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Ошибка при одобрении пользователя.']);
        }
    }
}