<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $product->increment('views');

        $product->load([
            'seller:id,username',
            'category:id,name',
            'reviews' => fn($q) => $q->where('approved', true)
                                     ->whereNull('answer_to_id')
                                     ->with(['user:id,username', 'replies.user:id,username'])
                                     ->latest('created_at'),
        ]);

        return view('products.show', compact('product'));
    }
}
