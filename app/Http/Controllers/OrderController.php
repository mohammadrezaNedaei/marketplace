<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if (Auth::user()->role !== 'buyer') {
            abort(403);
        }

        if ($product->status !== 'active') {
            abort(404);
        }

        $alreadyBought = Order::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->where('status', 'paid')
            ->exists();

        if ($alreadyBought) {
            return redirect()->route('products.show', $product)
                ->with('error', 'شما قبلاً این محصول را خریداری کرده‌اید');
        }

        $price = $product->discount_price ?? $product->price;

        // ایجاد سفارش
        $order = Order::create([
            'user_id' => Auth::id(),
            'product_id'=> $product->id,
            'quantity' => 1,
            'amount' => $price,
            'status' => 'paid',
            'payment_gateway' => 'fake',
            'transaction_id' => 'FAKE-' . strtoupper(uniqid()),
        ]);

        $product->increment('sales_count');

        return redirect()->route('orders.show', $order)
            ->with('success', 'خرید با موفقیت انجام شد');
    }

    public function show(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['product.seller', 'product.category']);

        return view('orders.show', compact('order'));
    }
}
