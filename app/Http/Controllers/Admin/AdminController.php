<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CardTransferRequest;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Models\WithdrawalRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Morilog\Jalali\Jalalian;

class AdminController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalSellers = User::where('role', 'seller')->count();
        $totalBuyers = User::where('role', 'buyer')->count();
        $openTickets = SupportTicket::where('status', 'open')->count();
        $totalProducts = Product::where('status', 'active')->count();
        $totalsales = Order::whereStatus('paid')->sum('amount');
        $totalCategories = Category::count();

        $recentActivities = collect()
            ->merge(
                User::latest('created_at')->take(5)->get()->map(fn ($u) => [
                    'type' => 'user',
                    'icon' => '👤',
                    'text' => $u->username.' ثبت‌نام کرد',
                    'created_at' => $u->created_at,
                ])
            )
            ->merge(
                Order::with('user')->latest('created_at')->take(5)->get()->map(fn ($o) => [
                    'type' => 'order',
                    'icon' => '🛍',
                    'text' => ($o->user->username ?? '—').' یک سفارش ثبت کرد',
                    'created_at' => $o->created_at,
                ])
            )
            ->merge(
                Product::with('seller')->latest('created_at')->take(5)->get()->map(fn ($p) => [
                    'type' => 'product',
                    'icon' => '📦',
                    'text' => ($p->seller->username ?? '—').' محصول جدید اضافه کرد: '.$p->title,
                    'created_at' => $p->created_at,
                ])
            )
            ->merge(
                SupportTicket::with('user')->latest('created_at')->take(5)->get()->map(fn ($t) => [
                    'type' => 'ticket',
                    'icon' => '🎫',
                    'text' => ($t->user->username ?? '—').' تیکت جدید باز کرد',
                    'created_at' => $t->created_at,
                ])
            )
            ->sortByDesc('created_at')
            ->take(10)
            ->values();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalSellers',
            'totalBuyers',
            'openTickets',
            'totalProducts',
            'recentActivities',
            'totalsales',
            'totalCategories'
        ));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = str_replace(['%', '_'], ['\\%', '\\_'], $request->search);
            $query->where('username', 'like', '%'.$search.'%', 'and');
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest('created_at')->paginate(20);

        return view('admin.users', compact('users'));
    }

    public function editUser(User $user)
    {
        return view('admin.edit-user', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'username' => 'required|string|max:50|unique:users,username,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:buyer,seller,admin',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:active,inactive',
        ]);

        $user->username = $request->username;
        $user->phone = $request->phone;

        if ($user->role === 'admin' && $request->role !== 'admin') {
            if (User::where('role', 'admin')->count() <= 1) {
                return back()->with('error', 'نمی‌توانید آخرین ادمین را تنزل دهید');
            }
        }

        $user->role = $request->role;
        $user->status = $request->status;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')
            ->with('success', 'اطلاعات کاربر با موفقیت ویرایش شد');
    }

    public function deleteUser(User $user)
    {
        // ادمین نمی‌تواند خودش را حذف کند
        if ($user->id === Auth::id()) {
            return back()->with('error', 'نمی‌توانید حساب خود را حذف کنید');
        }

        $user->delete();

        return redirect()->route('admin.users')
            ->with('success', 'کاربر با موفقیت حذف شد');
    }

    public function tickets(Request $request)
    {
        $query = SupportTicket::with('user')->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->paginate(20);

        return view('admin.tickets', compact('tickets'));
    }

    public function showTicket(SupportTicket $ticket)
    {
        $ticket->load(['user', 'messages.sender']);

        return view('admin.show-ticket', compact('ticket'));
    }

    public function replyTicket(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message,
        ]);

        $ticket->status = 'answered';
        $ticket->save();

        return back()->with('success', 'پاسخ با موفقیت ارسال شد');
    }

    public function updateTicketStatus(Request $request, SupportTicket $ticket)
    {
        $request->validate([
            'status' => 'required|in:open,answered,closed',
        ]);

        $ticket->status = $request->status;
        $ticket->save();

        return back()->with('success', 'وضعیت تیکت بروزرسانی شد');
    }

    public function products(Request $request)
    {
        $query = Product::with(['seller', 'category']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $products = $query->latest('created_at')->paginate(20);

        return view('admin.products', compact('products'));
    }

    public function editProduct(Product $product)
    {
        $categories = Category::all();

        return view('admin.edit-product', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, Product $product)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'discount_price' => 'nullable|numeric|min:0|lt:price',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:active,inactive',
        ]);

        $product->title = $request->title;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->discount_price = $request->discount_price;
        $product->category_id = $request->category_id;
        $product->status = $request->status;
        $product->save();

        return redirect()->route('admin.products')
            ->with('success', 'محصول با موفقیت ویرایش شد');
    }

    public function deleteProduct(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products')
            ->with('success', 'محصول با موفقیت حذف شد');
    }

    public function withdrawals(Request $request)
    {
        $query = WithdrawalRequest::with('user')->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $fromDate = $this->jalaliToGregorian($request->from_date);
            if ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            }
        }

        if ($request->filled('to_date')) {
            $toDate = $this->jalaliToGregorian($request->to_date);
            if ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }
        }

        $withdrawals = $query->paginate(20)->withQueryString();

        return view('admin.withdrawals', compact('withdrawals'));
    }

    private function jalaliToGregorian(string $jalaliDate): ?string
    {
        try {
            $normalized = strtr($jalaliDate, [
                '۰' => '0',
                '۱' => '1',
                '۲' => '2',
                '۳' => '3',
                '۴' => '4',
                '۵' => '5',
                '۶' => '6',
                '۷' => '7',
                '۸' => '8',
                '۹' => '9',
            ]);

            return Jalalian::fromFormat('Y/m/d', $normalized)
                ->toCarbon()
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function approveWithdrawal(WithdrawalRequest $withdrawal)
    {
        DB::transaction(function () use ($withdrawal) {
            $withdrawal = WithdrawalRequest::lockForUpdate()->find($withdrawal->id);

            if ($withdrawal->status !== 'pending') {
                return back()->with('error', 'این درخواست قبلاً بررسی شده است');
            }

            $seller = User::lockForUpdate()->find($withdrawal->user_id);

            if ($seller->wallet_balance < $withdrawal->amount) {
                return back()->with('error', 'موجودی فروشنده کافی نیست');
            }

            $seller->decrement('wallet_balance', $withdrawal->amount);

            WalletTransaction::create([
                'user_id' => $seller->id,
                'type' => 'withdrawal',
                'amount' => $withdrawal->amount,
            ]);

            $withdrawal->status = 'approved';
            $withdrawal->reviewed_at = now();
            $withdrawal->save();
        });

        return back()->with('success', 'درخواست برداشت تایید شد');
    }

    public function rejectWithdrawal(WithdrawalRequest $withdrawal)
    {
        if ($withdrawal->status !== 'pending') {
            return back()->with('error', 'این درخواست قبلاً بررسی شده است');
        }

        $withdrawal->status = 'rejected';
        $withdrawal->reviewed_at = now();
        $withdrawal->save();

        return back()->with('success', 'درخواست برداشت رد شد');
    }

    public function orders(Request $request)
    {
        $query = Order::with(['user', 'product']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $fromDate = $this->jalaliToGregorian($request->from_date);
            if ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            }
        }

        if ($request->filled('to_date')) {
            $toDate = $this->jalaliToGregorian($request->to_date);
            if ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }
        }

        $orders = $query->latest('created_at')->paginate(10)->withQueryString();

        return view('admin.orders', compact('orders'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:paid,processing,completed,canceled',
        ], [
            'status.required' => 'وضعیت سفارش الزامی است',
            'status.in' => 'وضعیت سفارش نامعتبر است',
        ]);

        if ($order->status === 'paid' && $request->status === 'canceled') {
            DB::transaction(function () use ($order) {
                User::where('id', $order->user_id)->increment('wallet_balance', $order->amount);
                User::where('id', $order->product->seller_id)->decrement('wallet_balance', $order->amount);

                WalletTransaction::create([
                    'user_id' => $order->user_id,
                    'type' => 'deposit',
                    'amount' => $order->amount,
                    'order_id' => $order->id,
                ]);

                WalletTransaction::create([
                    'user_id' => $order->product->seller_id,
                    'type' => 'income',
                    'amount' => -$order->amount,
                    'order_id' => $order->id,
                ]);

                $order->status = 'canceled';
                $order->save();
            });
        } else {
            $order->status = $request->status;
            $order->save();
        }

        return back()->with('success', 'وضعیت سفارش بروزرسانی شد');
    }

    public function activityLog(Request $request)
    {
        $query = DB::table('activity_log_view');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('actor', 'like', "%{$search}%")
                    ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('from_date')) {
            $fromDate = $this->jalaliToGregorian($request->from_date);
            if ($fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            }
        }

        if ($request->filled('to_date')) {
            $toDate = $this->jalaliToGregorian($request->to_date);
            if ($toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            }
        }

        $activityLog = $query->orderByDesc('created_at')->paginate(9)->withQueryString();

        return view('admin.activity-log', compact('activityLog'));
    }

    public function cardTransfers(Request $request)
    {
        $query = CardTransferRequest::with('user')->latest('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', fn ($u) => $u->where('username', 'like', '%'.$request->search.'%'));
        }

        $cardTransfers = $query->paginate(20)->withQueryString();

        return view('admin.card-transfers', compact('cardTransfers'));
    }

    public function approveCardTransfer(CardTransferRequest $cardTransfer)
    {
        DB::transaction(function () use ($cardTransfer) {
            $cardTransfer = CardTransferRequest::lockForUpdate()->find($cardTransfer->id);

            if ($cardTransfer->status !== 'pending') {
                return back()->with('error', 'این درخواست قبلاً بررسی شده است');
            }

            $user = User::lockForUpdate()->find($cardTransfer->user_id);

            $user->increment('wallet_balance', $cardTransfer->amount);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $cardTransfer->amount,
                'gateway' => 'card_transfer',
                'transaction_id' => 'CT-'.$cardTransfer->id,
            ]);

            $cardTransfer->status = 'approved';
            $cardTransfer->reviewed_at = now();
            $cardTransfer->save();
        });

        return back()->with('success', 'درخواست تایید شد و کیف پول کاربر شارژ شد');
    }

    public function rejectCardTransfer(Request $request, CardTransferRequest $cardTransfer)
    {
        if ($cardTransfer->status !== 'pending') {
            return back()->with('error', 'این درخواست قبلاً بررسی شده است');
        }

        $cardTransfer->status = 'rejected';
        $cardTransfer->admin_note = $request->admin_note;
        $cardTransfer->reviewed_at = now();
        $cardTransfer->save();

        return back()->with('success', 'درخواست رد شد');
    }
}
