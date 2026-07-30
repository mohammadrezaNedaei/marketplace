@extends('layouts.app')

@section('title', 'داشبورد فروشنده')

@section('content')
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">محصولات من</h1>
        <div class="flex gap-3">
            <a href="{{ route('seller.analytics') }}"
                class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
                آنالیتیکس
            </a>
            <a href="{{ route('profile.edit') }}"
                class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
                ویرایش پروفایل
            </a>
            <a href="{{ route('seller.products.create') }}"
                class="bg-black text-white px-5 py-2 rounded-full text-sm hover:bg-gray-800">
                + افزودن محصول
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if ($products->isEmpty())
        <div class="text-center text-gray-400 mt-20">
            <p class="text-lg">هنوز محصولی اضافه نکرده‌اید</p>
            <a href="{{ route('seller.products.create') }}" class="text-black underline text-sm mt-2 inline-block">
                اولین محصول را اضافه کنید
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach ($products as $product)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                    <img src="{{ asset('storage/' . $product->picture_url) }}" alt="{{ $product->title }}"
                        class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="font-bold text-lg mb-1">{{ $product->title }}</h2>
                        <p class="text-gray-500 text-sm mb-3">{{ $product->category->name }}</p>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold">{{ number_format($product->price) }} تومان</span>
                            <span class="text-gray-400">{{ $product->views }} بازدید · {{ $product->sales_count }}
                                فروش</span>
                        </div>
                        <a href="{{ route('seller.products.edit', $product) }}"
                            class="mt-3 block text-center border border-gray-300 text-sm py-1.5 rounded-lg hover:bg-gray-50 transition">
                            ویرایش
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
