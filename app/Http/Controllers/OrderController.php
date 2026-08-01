<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $buyer = Auth::user();

        if ($buyer->wallet_balance < $price) {
        return redirect()->route('products.show', $product)
                         ->with('error', 'موجودی کیف پول شما کافی نیست. لطفاً ابتدا کیف پول خود را شارژ کنید.');
        }

        DB::transaction(function () use ($buyer, $product, $price, &$order) {
        $buyer->decrement('wallet_balance', $price);

        $order = Order::create([
            'user_id'         => $buyer->id,
            'product_id'      => $product->id,
            'quantity'        => 1,
            'amount'          => $price,
            'status'          => 'paid',
            'payment_gateway' => 'wallet',
            'transaction_id'  => 'ORD-' . strtoupper(uniqid()),
        ]);

        WalletTransaction::create([
            'user_id'  => $buyer->id,
            'type'     => 'purchase',
            'amount'   => $price,
            'order_id' => $order->id,
        ]);

        $seller = $product->seller;
        $seller->increment('wallet_balance', $price);

        WalletTransaction::create([
            'user_id'  => $seller->id,
            'type'     => 'income',
            'amount'   => $price,
            'order_id' => $order->id,
        ]);

        $product->increment('sales_count');
    });

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
