<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:users,username',
            'phone' => 'required|string|max:11|unique:users,phone',
            'password'         => 'nullable|string|min:6|confirmed',
            'current_password' => 'required_with:password|string',
        ]);

        $user = Auth::user();
        $user->username = $request->username;
        $user->phone = $request->phone;

        if ($request->filled('password')) {
            if(!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'رمز فعلی اشتباه است']);
            }
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile.edit')->with('success', 'اطلاعات با موفقیت بروزرسانی شد');
    }
}
