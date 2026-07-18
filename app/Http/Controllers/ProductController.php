<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductView;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        if ($product->status !== 'active') {
            abort(404);
        }

        $this->recordView($product);

        $product->load([
            'seller:id,username',
            'category:id,name',
            'reviews' => fn($q) => $q->where('approved', true)
                ->whereNull('answer_to_id')
                ->with(['user:id,username', 'replies.user:id,username'])
                ->latest('created_at'),
        ]);
        if (Auth::check()) {
            Auth::user()->load('orders');
        }

        return view('products.show', compact('product'));
    }

    private function recordView(Product $product): void
    {
        $isOwner = Auth::check() && Auth::id() === $product->seller_id;

        if ($isOwner) {
            return;
        }

        if (Auth::check()) {
            $alreadyViewed = ProductView::where('product_id', $product->id)
                ->where('user_id', Auth::id())
                ->exists();

            if (!$alreadyViewed) {
                ProductView::create([
                    'product_id' => $product->id,
                    'user_id' => Auth::id(),
                ]);
                $product->increment('views');
            }
        } else {
            $viewedKey = 'viewed_product_' . $product->id;

            if (!session()->has($viewedKey)) {
                session()->put($viewedKey, true);
                $product->increment('views');
            }
        }
    }

    public function toggleSave(Product $product)
    {
        if (!Auth::check() || Auth::user()->role !== 'buyer') {
            abort(403);
        }

        $existing = \App\Models\Save::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $saved = false;
        } else {
            \App\Models\Save::create([
                'user_id'    => Auth::id(),
                'product_id' => $product->id,
            ]);
            $saved = true;
        }

        return back()->with('saved', $saved);
    }

}
