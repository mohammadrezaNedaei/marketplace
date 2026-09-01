<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function edit()
    {
        return view('seller.profile');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'bio'    => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'bio.max'     => 'بیوگرافی نباید بیشتر از ۵۰۰ کاراکتر باشد.',
            'avatar.image' => 'فایل باید تصویر باشد.',
            'avatar.mimes' => 'فرمت عکس باید jpg، jpeg یا png باشد.',
            'avatar.max'   => 'حجم عکس نباید بیشتر از ۲ مگابایت باشد.',
        ]);

        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('avatars', 'public');
        }

        $user->bio = $request->bio;
        $user->save();

        return redirect()->route('seller.profile.edit')
                         ->with('success', 'فروشگاه شما با موفقیت بروزرسانی شد');
    }
}
