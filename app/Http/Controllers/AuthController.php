<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerForm() {
        return view('auth.register');
    }

    public function loginForm() {
    return view('auth.login');
}

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|string|min:3|max:50|unique:users',
            'phone'    => 'required|string|regex:/^09[0-9]{9}$/|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:buyer,seller',
        ]);

        $user = User::create([
            'username' => $request->username,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }

    public function login(Request $request) {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'username' => 'نام کاربری یا رمز عبور اشتباه است',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
