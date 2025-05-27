<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdateProfileRequest;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function update(UpdateProfileRequest $request)
    {
   
        User::updateProfile($request->all(), $request->user()->id);
        return redirect()->route('profile.show')->with('success', 'Профиль обновлен');
    }

}