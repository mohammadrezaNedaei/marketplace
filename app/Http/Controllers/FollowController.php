<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function toggle(User $seller)
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403);
        }

        if ($seller->role !== 'seller') {
            abort(404);
        }

        $existing = Follow::where('follower_id', Auth::id())
                          ->where('seller_id', $seller->id)
                          ->first();

        if ($existing) {
            $existing->delete();
        } else {
            Follow::create([
                'follower_id' => Auth::id(),
                'seller_id'   => $seller->id,
            ]);
        }

        return back();
    }
}
