@extends('layouts.app')

@section('title', 'کیف پول')

@section('content')

    <div class="max-w-2xl mx-auto">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">کیف پول</h1>
            <div class="flex gap-3">
                @if (Auth::user()->role === 'seller')
                    <a href="{{ route('wallet.withdraw.form') }}"
                        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
                        برداشت وجه
                    </a>
                @endif
                <a href="{{ route('wallet.deposit.form') }}"
                    class="bg-black text-white px-5 py-2 rounded-full text-sm hover:bg-gray-800 transition">
                    + شارژ کیف پول
                </a>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-8 text-center">
            <p class="text-gray-400 text-sm mb-2">موجودی کیف پول</p>
            <p class="text-4xl font-bold">{{ number_format(Auth::user()->wallet_balance) }} <span
                    class="text-lg text-gray-400">تومان</span></p>
        </div>

        <h2 class="text-lg font-bold mb-4">تاریخچه تراکنش‌ها</h2>

        <form method="GET" action="{{ route('wallet.index') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">

                <select name="type"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <option value="">همه انواع</option>
                    <option value="deposit" {{ request('type') == 'deposit' ? 'selected' : '' }}>شارژ کیف پول</option>
                    <option value="purchase" {{ request('type') == 'purchase' ? 'selected' : '' }}>خرید محصول</option>
                    @if (Auth::user()->role === 'seller')
                        <option value="income" {{ request('type') == 'income' ? 'selected' : '' }}>درآمد فروش</option>
                        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>برداشت وجه
                        </option>
                    @endif
                </select>

                <input type="text" name="from_date" value="{{ request('from_date') }}" data-jdp dir="ltr" readonly
                    autocomplete="off" placeholder="از تاریخ"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

                <input type="text" name="to_date" value="{{ request('to_date') }}" data-jdp dir="ltr" readonly
                    autocomplete="off" placeholder="تا تاریخ"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

                <input type="number" name="min_amount" value="{{ request('min_amount') }}" placeholder="حداقل مبلغ"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">

                <input type="number" name="max_amount" value="{{ request('max_amount') }}" placeholder="حداکثر مبلغ"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <div>
                <button type="submit"
                    class="bg-black text-white px-6 py-2 mb-4 w-full rounded-lg text-sm hover:bg-gray-800 transition">
                    اعمال فیلتر
                </button>
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
                        <ul class="list-disc mr-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if (request()->hasAny(['type', 'from_date', 'to_date', 'min_amount', 'max_amount']))
                    <a href="{{ route('wallet.index') }}"
                        class="block w-full border border-gray-300 px-6 py-2 rounded-lg text-sm hover:bg-gray-50 transition text-center">
                        پاک کردن فیلترها
                    </a>
                @endif
            </div>
        </form>
        @if ($transactions->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
                <p>هنوز تراکنشی ثبت نشده</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @foreach ($transactions as $transaction)
                    @php
                        $typeLabel = match ($transaction['type']) {
                            'deposit' => 'شارژ کیف پول',
                            'purchase' => 'خرید محصول',
                            'income' => 'درآمد فروش',
                            'withdrawal' => 'درخواست برداشت وجه',
                        };
                        $isPositive = in_array($transaction['type'], ['deposit', 'income']);

                        $statusLabel = match ($transaction['status'] ?? null) {
                            'pending' => 'در انتظار تایید',
                            'rejected' => 'رد شده',
                            default => null,
                        };
                    @endphp
                    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 last:border-0">
                        <div>
                            <p class="font-medium text-sm">{{ $typeLabel }}</p>
                            <p class="text-gray-400 text-xs mt-0.5">
                                {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($transaction['created_at']))->format('Y/m/d H:i') }}
                                @if ($statusLabel)
                                    · <span
                                        class="{{ $transaction['status'] === 'rejected' ? 'text-red-500' : 'text-yellow-600' }}">{{ $statusLabel }}</span>
                                @endif
                            </p>
                        </div>
                        <span class="font-bold text-sm {{ $isPositive ? 'text-green-600' : 'text-red-500' }}">
                            {{ $isPositive ? '+' : '-' }}{{ number_format($transaction['amount']) }} تومان
                        </span>
                    </div>
                @endforeach
            </div>

            @if ($transactions->hasPages())
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @endif
        @endif
    </div>
    <script>
        jalaliDatepicker.startWatch({
            persianDigits: true,
            autoHide: true,
            autoReadOnlyInput: true,
        });
    </script>
@endsection
