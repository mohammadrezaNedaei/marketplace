@extends('layouts.app')

@section('title', 'روش شارژ کیف پول')

@section('content')

<div class="max-w-md mx-auto" x-data="{ amount: '' }">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">شارژ کیف پول</h1>
        <a href="{{ route('wallet.index') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <label class="block text-sm font-medium mb-2">مبلغ مورد نظر (تومان)</label>
        <input type="number" x-model="amount" min="1000" step="1000"
            placeholder="مثلاً 50000"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black mb-3">

        <div class="flex gap-2">
            @foreach([20000, 50000, 100000, 200000] as $suggested)
                <button type="button" @click="amount = {{ $suggested }}"
                    class="border border-gray-300 px-3 py-1.5 rounded-lg text-xs hover:bg-gray-50 transition">
                    {{ number_format($suggested) }}
                </button>
            @endforeach
        </div>
    </div>

    <p class="text-gray-500 text-sm mb-4 text-center">روش پرداخت مورد نظر خود را انتخاب کنید</p>

    <div class="space-y-4">

        <a :href="amount >= 1000 ? '{{ route('wallet.deposit.form') }}?amount=' + amount : '#'"
            :class="amount < 1000 && 'opacity-40 pointer-events-none'"
            class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-gray-200 transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-50 rounded-xl flex items-center justify-center text-2xl shrink-0">
                    💳
                </div>
                <div class="flex-1">
                    <h2 class="font-bold mb-1">پرداخت آنلاین</h2>
                    <p class="text-gray-400 text-sm">پرداخت سریع و آنی از طریق درگاه زرین‌پال</p>
                </div>
                <span class="text-gray-300 group-hover:text-gray-500 transition">←</span>
            </div>
        </a>

        <a :href="amount >= 1000 ? '{{ route('wallet.card-transfer.form') }}?amount=' + amount : '#'"
            :class="amount < 1000 && 'opacity-40 pointer-events-none'"
            class="block bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md hover:border-gray-200 transition group">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-2xl shrink-0">
                    🏦
                </div>
                <div class="flex-1">
                    <h2 class="font-bold mb-1">کارت به کارت</h2>
                    <p class="text-gray-400 text-sm">واریز مستقیم و تایید توسط پشتیبانی (تا ۲۴ ساعت)</p>
                </div>
                <span class="text-gray-300 group-hover:text-gray-500 transition">←</span>
            </div>
        </a>

    </div>
</div>

@endsection
