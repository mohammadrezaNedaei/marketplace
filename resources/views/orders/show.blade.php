@extends('layouts.app')

@section('title', 'جزئیات سفارش')

@section('content')
<div class="max-w-2xl mx-auto">

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">

        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                <span class="text-green-600 text-lg">✓</span>
            </div>
            <div>
                <h1 class="text-xl font-bold">سفارش تایید شد</h1>
                <p class="text-gray-400 text-sm">کد پیگیری: {{ $order->transaction_id }}</p>
            </div>
        </div>

        {{-- اطلاعات محصول --}}
        <div class="flex gap-4 mb-8 pb-8 border-b border-gray-100">
            <img src="{{ asset('storage/' . $order->product->picture_url) }}"
                 class="w-20 h-20 object-cover rounded-xl">
            <div>
                <h2 class="font-bold mb-1">{{ $order->product->title }}</h2>
                <p class="text-gray-400 text-sm mb-1">{{ $order->product->seller->username }}</p>
                <p class="text-gray-400 text-sm">{{ $order->product->category->name }}</p>
            </div>
        </div>

        {{-- جزئیات پرداخت --}}
        <div class="space-y-3 mb-8">
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">مبلغ پرداختی</span>
                <span class="font-bold">{{ number_format($order->amount) }} تومان</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">تعداد</span>
                <span>{{ $order->quantity }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">وضعیت</span>
                <span class="text-green-600 font-medium">پرداخت شده</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-gray-500">درگاه پرداخت</span>
                <span>{{ $order->payment_gateway }}</span>
            </div>
        </div>

        {{-- دسترسی به فایل دیجیتال --}}
        @if($order->product->file_url)
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <p class="text-sm font-medium mb-2">فایل دیجیتال شما آماده است</p>
                <a href="{{ asset('storage/' . $order->product->file_url) }}"
                    download
                    class="inline-block bg-black text-white px-6 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
                    دانلود فایل
                </a>
            </div>
        @endif

        <a href="{{ route('explore') }}"
            class="block text-center border border-gray-300 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            بازگشت به کاوش
        </a>

    </div>
</div>
@endsection