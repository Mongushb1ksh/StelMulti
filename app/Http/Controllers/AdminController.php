<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = User::getDashboardStats();
        return view('admin.dashboard', compact('stats'));
    }

    public function index()
    {
        $users = User::getUsersList();
        $stats = User::getDashboardStats();
        return view('admin.users.index', compact('users', 'stats'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        try {
            User::createUser($request->all());
            return redirect()->route('admin.users.index')
                ->with('success', 'Пользователь создан');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        try {
            User::updateUser($request->all(), $user->id);
            return redirect()->route('admin.users.index')
                ->with('success', 'Пользователь обновлен');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function approve(User $user)
    {
        try {
            User::approveUser($user->id);
            return back()->with('success', 'Пользователь одобрен');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function block(User $user)
    {
        try {
            User::blockUser($user->id);
            return back()->with('success', 'Пользователь заблокирован');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        try {
            User::deleteUser($user->id);
            return redirect()->route('admin.users.index')
                ->with('success', 'Пользователь удален');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}