<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class SellerProfileController extends Controller
{
    public function show(User $seller)
    {
        if ($seller->role !== 'seller') {
            abort(404);
        }

        $stats = [
            'total_sales'      => $seller->products()->sum('sales_count'),
            'total_views'      => $seller->products()->sum('views'),
            'active_products'  => $seller->products()->where('status', 'active')->count(),
            'average_rating'   => $seller->products()
                                         ->join('reviews', 'reviews.product_id', '=', 'products.id')
                                         ->where('reviews.approved', true)
                                         ->whereNotNull('reviews.rating')
                                         ->avg('reviews.rating'),
            'followers_count'  => $seller->followers()->count(),
        ];

        $products = $seller->products()
                           ->where('status', 'active')
                           ->latest('created_at')
                           ->paginate(8);

        $reviews = \App\Models\Review::whereHas('product', fn($q) => $q->where('seller_id', $seller->id))
                                     ->where('approved', true)
                                     ->whereNull('answer_to_id')
                                     ->with(['user:id,username', 'product:id,title'])
                                     ->orderByDesc('rating')
                                     ->paginate(5, ['*'], 'reviews_page');

        $isFollowing = false;
        if (Auth::check() && Auth::user()->role === 'buyer') {
            $isFollowing = $seller->followers()->where('follower_id', Auth::id())->exists();
        }

        return view('seller.show', compact('seller', 'stats', 'products', 'reviews', 'isFollowing'));
    }
}
