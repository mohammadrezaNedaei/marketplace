@extends('layouts.app')

@section('title', 'درخواست برداشت')

@section('content')

    <div class="max-w-md mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">درخواست برداشت</h1>
            <a href="{{ route('wallet.index') }}"
                class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
                بازگشت
            </a>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
            <p class="text-gray-400 text-sm mb-1">موجودی قابل برداشت</p>
            <p class="text-3xl font-bold mb-8">{{ number_format(Auth::user()->wallet_balance) }} تومان</p>

            <form method="POST" action="{{ route('wallet.withdraw') }}">
                @csrf

                <label class="block text-sm font-medium mb-2">مبلغ برداشت (تومان)</label>
                <div class="flex gap-2">
                    <input type="number" name="amount" min="10000" step="1000" placeholder="حداقل 10,000 تومان"
                        class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">

                    <button type="button"
                        onclick="document.querySelector('[name=amount]').value = {{ auth()->user()->wallet_balance }}"
                        class="border border-gray-300 px-4 py-3 rounded-lg text-xs hover:bg-gray-50 transition whitespace-nowrap">
                        کل موجودی
                    </button>
                </div>
        </div>
        @error('amount')
            <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
        @enderror

        <button type="submit" class="w-full bg-black text-white py-3 mt-8 rounded-xl hover:bg-gray-800 transition font-medium">
            ثبت درخواست
        </button>
        </form>
    </div>
    </div>

@endsection
