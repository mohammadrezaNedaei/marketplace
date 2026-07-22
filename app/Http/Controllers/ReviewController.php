<?php

namespace App\Http\Controllers;

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

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
            'rating'     => $request->rating,
            'comment'    => $request->comment,
            'approved'   => true,
        ]);

        return back()->with('success', 'نظر شما با موفقیت ثبت شد');
    }

    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        Review::create([
            'product_id'   => $review->product_id,
            'user_id'      => Auth::id(),
            'answer_to_id' => $review->id,
            'comment'      => $request->comment,
            'approved'     => true,
        ]);

        return back()->with('success', 'پاسخ شما ثبت شد');
    }
}