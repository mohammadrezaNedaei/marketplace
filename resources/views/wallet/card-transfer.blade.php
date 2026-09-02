@extends('layouts.app')

@section('title', 'کارت به کارت')

@section('content')

    <div class="max-w-2xl mx-auto">

        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-bold text-gray-800">کارت به کارت</h1>
            <a href="{{ route('wallet.index') }}" class="text-sm text-gray-500 hover:text-gray-800 transition">
                ← بازگشت
            </a>
        </div>

        <div class="relative rounded-3xl p-6 mb-6 overflow-hidden card-gradient-bg">

            <div class="absolute w-72 h-72 rounded-full opacity-60 blur-3xl moving-glow"
                style="background: radial-gradient(circle, var(--color-sage-light), transparent 70%);"></div>

            <div class="relative z-10">
                <span
                    class="inline-block bg-white/70 backdrop-blur text-xs text-bronze px-3 py-1.5 rounded-full mb-6 shadow-sm">
                    واریز کارت به کارت
                </span>

                <h2 class="text-base font-bold text-ink mb-4">اطلاعات واریز</h2>

                <div class="grid grid-cols-2 gap-3">
                    <div class="glass-card col-span-2">
                        <p class="text-xs text-bronze mb-1">شماره کارت</p>
                        <p class="text-lg font-bold text-ink tracking-widest" dir="ltr">
                            {{ config('services.card_transfer.card_number') }}
                        </p>
                    </div>

                    <div class="glass-card">
                        <p class="text-xs text-bronze mb-1">به نام</p>
                        <p class="font-bold text-ink">{{ config('services.card_transfer.card_owner') }}</p>
                    </div>

                    <div class="glass-card">
                        <p class="text-xs text-bronze mb-1">زمان تایید</p>
                        <p class="font-bold text-sage">تا ۲۴ ساعت</p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-3">
                    @if (request('amount'))
                        <div class="glass-card text-center ">
                            <p class="text-xs text-bronze mb-1">مبلغی که باید واریز کنید</p>
                            <p class="text-xl font-bold text-sage">{{ number_format(request('amount')) }} تومان</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm p-6 space-y-5">
            <h2 class="text-base font-bold text-gray-700 mb-1">اطلاعات پرداخت شما</h2>

            <form method="POST" action="{{ route('wallet.card-transfer.store') }}" enctype="multipart/form-data"
                class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs text-gray-400 mb-2">مبلغ واریزی (تومان)</label>
                    @if (request('amount'))
                        <input type="hidden" name="amount" value="{{ request('amount') }}">
                        <div class="w-full bg-gray-50 rounded-2xl px-4 py-3 text-sm text-gray-700 font-medium">
                            {{ number_format(request('amount')) }} تومان
                        </div>
                    @else
                        <input type="number" name="amount" min="1000" step="1000"
                            class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
                    @endif
                    @error('amount')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-2">شماره پیگیری (اختیاری)</label>
                    <input type="text" name="tracking_code"
                        class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-200">
                    @error('tracking_code')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-xs text-gray-400 mb-2">عکس رسید</label>
                    <input type="file" name="receipt_image" accept="image/*"
                        class="w-full bg-gray-50 border-0 rounded-2xl px-4 py-3 text-sm">
                    @error('receipt_image')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                    class="w-full text-white py-3.5 rounded-2xl font-medium transition hover:opacity-90 bg-sage">
                    ثبت درخواست
                </button>
            </form>
        </div>

    </div>

@endsection
