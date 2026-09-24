<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

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
        $order = null;

        DB::transaction(function () use ($product, $price, &$order) {
            $buyer = User::lockForUpdate()->find(Auth::id());

            if ($buyer->wallet_balance < $price) {
                throw ValidationException::withMessages([
                    'wallet' => 'موجودی کیف پول شما کافی نیست.',
                ]);
            }

            $buyer->decrement('wallet_balance', $price);

            $order = Order::create([
                'user_id' => $buyer->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'amount' => $price,
                'status' => 'paid',
                'payment_gateway' => 'wallet',
                'transaction_id' => 'ORD-'.strtoupper(uniqid()),
            ]);

            WalletTransaction::create([
                'user_id' => $buyer->id,
                'type' => 'purchase',
                'amount' => $price,
                'order_id' => $order->id,
            ]);

            $seller = $product->seller;
            $seller->increment('wallet_balance', $price);

            Review::where('product_id', $product->id)
                ->where('user_id', $buyer->id)
                ->whereNull('answer_to_id')
                ->update(['verified_purchase' => true]);

            WalletTransaction::create([
                'user_id' => $seller->id,
                'type' => 'income',
                'amount' => $price,
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

    public function pay(Order $order)
    {

        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'pending') {
            return redirect()->route('buyer.payments')->with('error', 'این سفارش قبلاً پردازش شده است');
        }

        DB::transaction(function () use ($order) {
            $order = Order::lockForUpdate()->find($order->id);

            if ($order->status !== 'pending') {
                throw ValidationException::withMessages([
                    'status' => 'این سفارش قبلاً پردازش شده است',
                ]);
            }

            $buyer = User::lockForUpdate()->find(Auth::id());

            if ($buyer->wallet_balance < $order->amount) {
                throw ValidationException::withMessages([
                    'wallet' => 'موجودی کیف پول شما کافی نیست.',
                ]);
            }

            $buyer->decrement('wallet_balance', $order->amount);

            $order->status = 'paid';
            $order->save();

            WalletTransaction::create([
                'user_id' => $buyer->id,
                'type' => 'purchase',
                'amount' => $order->amount,
                'order_id' => $order->id,
            ]);

            $seller = $order->product->seller;
            $seller->increment('wallet_balance', $order->amount);

            Review::where('product_id', $order->product_id)
                ->where('user_id', $buyer->id)
                ->whereNull('answer_to_id')
                ->update(['verified_purchase' => true]);

            WalletTransaction::create([
                'user_id' => $seller->id,
                'type' => 'income',
                'amount' => $order->amount,
                'order_id' => $order->id,
            ]);

            $order->product->increment('sales_count');
        });

        return redirect()->route('orders.show', $order)->with('success', 'پرداخت با موفقیت انجام شد');

    }
}
