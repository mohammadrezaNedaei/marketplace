@extends('layouts.app')

@section('title', 'شارژ کیف پول')

@section('content')

<div class="max-w-md mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">شارژ کیف پول</h1>
        <a href="{{ route('wallet.index') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <p class="text-gray-400 text-sm mb-1">موجودی فعلی</p>
        <p class="text-3xl font-bold mb-8">{{ number_format(Auth::user()->wallet_balance) }} تومان</p>

        <form method="POST" action="{{ route('wallet.deposit') }}">
            @csrf

            <label class="block text-sm font-medium mb-2">مبلغ شارژ (تومان)</label>
            <input type="number" name="amount"
                placeholder="مثلاً 50000"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black mb-3">
            @error('amount')
                <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
            @enderror

            <div class="flex justify-between mb-6">
                @foreach([100000, 150000, 500000, 1000000] as $amount)
                    <button type="button"
                        onclick="document.querySelector('[name=amount]').value = {{ $amount }}"
                        class="border border-gray-300 px-6 py-1.5 rounded-lg text-xs hover:bg-gray-50 transition">
                        {{ number_format($amount) }}
                    </button>
                @endforeach
            </div>

            <button type="submit"
                class="w-full bg-black text-white py-3 rounded-xl hover:bg-gray-800 transition font-medium">
                شارژ کیف پول
            </button>
        </form>
    </div>
</div>

@endsection
