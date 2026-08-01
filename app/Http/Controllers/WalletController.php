<?php

namespace App\Http\Controllers;

use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WalletController extends Controller
{
    public function index(Request $request)
    {
        $request->validate(
            [
                'type' => 'nullable|in:deposit,income,purchase',
                'min_amount' => 'nullable|numeric|min:1000',
                'max_amount' => 'nullable|numeric|gte:min_amount',
                'from_date' => 'nullable|date_format:Y/m/d',
                'to_date' => 'nullable|date_format:Y/m/d|after_or_equal:from_date',
            ],
            [
                'type.in' => 'نوع تراکنش انتخاب شده معتبر نیست.',

                'min_amount.numeric' => 'حداقل مبلغ باید عدد باشد.',
                'min_amount.min' => 'حداقل مبلغ باید حداقل ۱۰۰۰ تومان باشد.',

                'max_amount.numeric' => 'حداکثر مبلغ باید عدد باشد.',
                'max_amount.gte' => 'حداکثر مبلغ باید بزرگ‌تر یا مساوی حداقل مبلغ باشد.',

                'from_date.date_format' => 'فرمت تاریخ شروع معتبر نیست.',
                'to_date.date_format' => 'فرمت تاریخ پایان معتبر نیست.',
                'to_date.after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع یا برابر با آن باشد.',
            ]
        );

        $walletQuery = WalletTransaction::where('user_id', Auth::id());

        if ($request->filled('type')) {
            $walletQuery->where('type', $request->type);
        }

        if ($request->filled('from_date')) {
            $fromDate = $this->jalaliToGregorian($request->from_date);
            if ($fromDate) $walletQuery->whereDate('created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = $this->jalaliToGregorian($request->to_date);
            if ($toDate) $walletQuery->whereDate('created_at', '<=', $toDate);
        }

        if ($request->filled('min_amount')) {
            $walletQuery->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $walletQuery->where('amount', '<=', $request->max_amount);
        }

        $walletItems = $walletQuery->get()->map(fn($t) => [
            'source'    => 'wallet',
            'type'      => $t->type,
            'amount'    => $t->amount,
            'status'    => null,
            'created_at' => $t->created_at,
        ]);

        $withdrawalQuery = \App\Models\WithdrawalRequest::where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'rejected']);

        if ($request->filled('from_date')) {
            $fromDate = $this->jalaliToGregorian($request->from_date);
            if ($fromDate) $withdrawalQuery->whereDate('created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = $this->jalaliToGregorian($request->to_date);
            if ($toDate) $withdrawalQuery->whereDate('created_at', '<=', $toDate);
        }

        if ($request->filled('min_amount')) {
            $withdrawalQuery->where('amount', '>=', $request->min_amount);
        }

        if ($request->filled('max_amount')) {
            $withdrawalQuery->where('amount', '<=', $request->max_amount);
        }

        if (!$request->filled('type') || $request->type === 'withdrawal') {
            $withdrawalItems = $withdrawalQuery->get()->map(fn($w) => [
                'source'    => 'withdrawal_request',
                'type'      => 'withdrawal',
                'amount'    => $w->amount,
                'status'    => $w->status,
                'created_at' => $w->created_at,
            ]);
        } else {
            $withdrawalItems = collect();
        }

        $allTransactions = $walletItems->merge($withdrawalItems)
            ->sortByDesc('created_at')
            ->values();

        $page    = $request->input('page', 1);
        $perPage = 10;
        $transactions = new \Illuminate\Pagination\LengthAwarePaginator(
            $allTransactions->forPage($page, $perPage),
            $allTransactions->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        return view('wallet.index', compact('transactions'));
    }

    public function depositForm()
    {
        return view('wallet.deposit');
    }

    public function deposit(Request $request)
    {

        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ], [
            'amount.required' => 'وارد کردن مبلغ الزامی است.',
            'amount.numeric' => 'حداقل مبلغ باید عدد باشد.',
            'amount.min' => 'حداقل مبلغ باید حداقل ۱۰۰۰ تومان باشد.',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($user, $request) {

            $user->increment('wallet_balance', $request->amount);

            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'gateway' => 'fake',
                'transaction_id' => 'DEP-' . strtoupper(uniqid()),
            ]);
        });

        return redirect()->route('wallet.index')->with('success', 'کیف پول با موفقیت شارژ شد');
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

            return \Morilog\Jalali\Jalalian::fromFormat('Y/m/d', $normalized)
                ->toCarbon()
                ->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function withdrawForm()
    {
        return view('wallet.withdraw');
    }

    public function withdraw(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'amount' => 'required|numeric|min:1000'
        ], [
            'amount.required' => 'وارد کردن مبلغ الزامی است.',
            'amount.numeric' => 'حداقل مبلغ باید عدد باشد.',
            'amount.min' => 'حداقل مبلغ باید حداقل ۱۰۰۰ تومان باشد.',
        ]);

        if ($request->amount > $user->wallet_balance) {
            return back()->withErrors(['amount' => 'موجودی کیف پول شما کافی نیست']);
        }

        \App\Models\WithdrawalRequest::create([
            'user_id' => $user->id,
            'amount'  => $request->amount,
            'status'  => 'pending',
        ]);

        return redirect()->route('wallet.index')
            ->with('success', 'درخواست برداشت شما ثبت شد و در انتظار تایید ادمین است');
    }
}
