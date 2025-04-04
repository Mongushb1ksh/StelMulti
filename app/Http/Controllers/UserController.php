<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(10); // Загружаем связь с ролью
        return view('admin.users.index', compact('users'));
    }

    public function edit(User $user)
    {
        $roles = \App\Models\Role::all();
        return view('admin.users.edit', compact('user'));
    }


    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $user->update($validatedData);

        return redirect()->route('admin.users.index')->with('success', 'Данные пользователя успешно обновлены.');
    }
}
