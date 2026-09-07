<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'buyer') {
            $items = $user->following()
                ->where('users.role', 'seller')
                ->withCount('followers')
                ->latest('follows.created_at')
                ->paginate(12);

            return view('follows.index', [
                'mode' => 'following',
                'title' => 'فروشگاه‌هایی که دنبال می‌کنید',
                'items' => $items,
            ]);
        }

        if ($user->role === 'seller') {
            $items = $user->followers()
                ->where('users.role', 'buyer')
                ->latest('follows.created_at')
                ->paginate(12);

            return view('follows.index', [
                'mode' => 'followers',
                'title' => 'دنبال‌کننده‌های من',
                'items' => $items,
            ]);
        }

        abort(403);
    }

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
            return back()->with('success', 'فروشگاه از دنبال‌شده‌ها حذف شد');
        }

        Follow::create([
            'follower_id' => Auth::id(),
            'seller_id' => $seller->id,
        ]);

        return back()->with('success', 'فروشگاه با موفقیت دنبال شد');
    }
}
