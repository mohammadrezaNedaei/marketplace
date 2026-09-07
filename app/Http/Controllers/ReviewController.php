<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403);
        }

        $validated = $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $verifiedPurchase = Order::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->whereIn('status', ['paid', 'delivered'])
            ->exists();

        $review = Review::where('product_id', $product->id)
            ->where('user_id', Auth::id())
            ->whereNull('answer_to_id')
            ->first();

        if ($review) {
            $review->update([
                'rating' => $validated['rating'],
                'comment' => $validated['comment'],
                'verified_purchase' => $verifiedPurchase,
                'approved' => true,
            ]);

            return back()->with('success', 'نظر شما با موفقیت ویرایش شد');
        }

        Review::create([
            'product_id' => $product->id,
            'user_id' => Auth::id(),
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
            'verified_purchase' => $verifiedPurchase,
            'approved' => true,
        ]);

        return back()->with('success', 'نظر شما با موفقیت ثبت شد');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $review->product_id,
            'user_id' => Auth::id(),
            'answer_to_id' => $review->id,
            'comment' => $request->comment,
            'approved' => true,
            'verified_purchase' => false,
        ]);

        return back()->with('success', 'پاسخ شما ثبت شد');
    }
}
