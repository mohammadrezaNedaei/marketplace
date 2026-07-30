<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Save;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('buyer.dashboard');
    }

    public function orders()
    {
        $orders = Order::where('user_id', Auth::id())
                       ->where('status', 'paid')
                       ->with('product.category')
                       ->latest('created_at')
                       ->paginate(3);

        return response()->json([
            'data' => $orders->map(fn($o) => [
                'id'           => $o->id,
                'title'        => $o->product->title,
                'category'     => $o->product->category->name,
                'amount'       => number_format($o->amount),
                'picture_url'  => asset('storage/' . $o->product->picture_url),
                'file_url'     => $o->product->file_url
                                    ? asset('storage/' . $o->product->file_url)
                                    : null,
                'order_url'    => route('orders.show', $o->id),
            ]),
            'current_page' => $orders->currentPage(),
            'last_page'    => $orders->lastPage(),
        ]);
    }

    public function saves()
    {
        $saves = Save::where('user_id', Auth::id())
                     ->with('product.category', 'product.seller')
                     ->latest('created_at')
                     ->paginate(4);

        return response()->json([
            'data' => $saves->map(fn($s) => [
                'id'          => $s->id,
                'title'       => $s->product->title,
                'category'    => $s->product->category->name,
                'seller'      => $s->product->seller->username,
                'price'       => number_format($s->product->discount_price ?? $s->product->price),
                'picture_url' => asset('storage/' . $s->product->picture_url),
                'url'         => route('products.show', $s->product->id),
            ]),
            'current_page' => $saves->currentPage(),
            'last_page'    => $saves->lastPage(),
        ]);
    }

    public function payments()
    {
        return view('buyer.payments');
    }

    public function paymentsApi(Request $request)
    {
        $query = Order::whereUserId(Auth::id())
                      ->with('product.category')
                      ->latest('created_at');

        if($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            $query->where('status', 'paid');
        }

        $orders = $query->paginate(10);

        return response()->json([
        'data' => $orders->map(fn($o) => [
            'id'          => $o->id,
            'title'       => $o->product->title,
            'category'    => $o->product->category->name,
            'amount'      => number_format($o->amount),
            'status'      => $o->status,
            'gateway'     => $o->payment_gateway,
            'transaction' => $o->transaction_id,
            'date'        => $o->created_at,
            'picture_url' => asset('storage/' . $o->product->picture_url),
            'order_url'   => route('orders.show', $o->id),
        ]),
        'current_page' => $orders->currentPage(),
        'last_page'    => $orders->lastPage(),
    ]);
    }
}
