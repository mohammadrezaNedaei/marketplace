@extends('layouts.app')

@section('title', $seller->username)

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- هدر پروفایل --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 mb-8">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">

            {{-- آواتار --}}
            <img src="{{ $seller->avatar ? asset('storage/' . $seller->avatar) : 'https://ui-avatars.com/api/?name=' . $seller->username . '&size=128' }}"
                 class="w-28 h-28 rounded-full object-cover shrink-0">

            <div class="flex-1 text-center md:text-right">
                <div class="flex flex-col md:flex-row md:items-center gap-3 mb-2">
                    <h1 class="text-2xl font-bold">{{ $seller->username }}</h1>

                    {{-- دکمه دنبال کردن --}}
                    @auth
                        @if(Auth::user()->role === 'buyer')
                            <form method="POST" action="{{ route('sellers.follow', $seller) }}">
                                @csrf
                                <button type="submit"
                                    class="px-5 py-1.5 rounded-full text-sm font-medium transition
                                        {{ $isFollowing
                                            ? 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                            : 'bg-black text-white hover:bg-gray-800' }}">
                                    {{ $isFollowing ? 'دنبال می‌کنید' : 'دنبال کردن' }}
                                </button>
                            </form>
                        @endif
                    @endauth
                </div>

                <p class="text-gray-400 text-sm mb-3">
                    عضو از {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($seller->created_at))->format('Y/m/d') }}
                    · {{ number_format($stats['followers_count']) }} دنبال‌کننده
                </p>

                @if($seller->bio)
                    <p class="text-gray-600 text-sm leading-relaxed">{{ $seller->bio }}</p>
                @endif
            </div>
        </div>

        {{-- آمار --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8 pt-8 border-t border-gray-100">
            <div class="text-center">
                <p class="text-xl font-bold">{{ number_format($stats['active_products']) }}</p>
                <p class="text-gray-400 text-xs mt-1">محصول فعال</p>
            </div>
            <div class="text-center">
                <p class="text-xl font-bold">{{ number_format($stats['total_sales']) }}</p>
                <p class="text-gray-400 text-xs mt-1">فروش</p>
            </div>
            <div class="text-center">
                <p class="text-xl font-bold">{{ number_format($stats['total_views']) }}</p>
                <p class="text-gray-400 text-xs mt-1">بازدید</p>
            </div>
            <div class="text-center">
                <p class="text-xl font-bold">
                    {{ $stats['average_rating'] ? number_format($stats['average_rating'], 1) : '—' }}
                    @if($stats['average_rating'])
                        <span class="text-yellow-500">⭐</span>
                    @endif
                </p>
                <p class="text-gray-400 text-xs mt-1">میانگین امتیاز</p>
            </div>
        </div>
    </div>

    {{-- محصولات --}}
    <div class="mb-10">
        <h2 class="text-lg font-bold mb-4">محصولات</h2>

        @if($products->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
                <p>هنوز محصولی ثبت نشده</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($products as $product)
                    <a href="{{ route('products.show', $product) }}"
                        class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition group">
                        <img src="{{ asset('storage/' . $product->picture_url) }}"
                             class="w-full h-36 object-cover group-hover:scale-105 transition duration-300">
                        <div class="p-3">
                            <h3 class="font-bold text-sm mb-1 line-clamp-1">{{ $product->title }}</h3>
                            <p class="text-gray-500 text-sm">
                                {{ number_format($product->discount_price ?? $product->price) }} تومان
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>

            @if($products->hasPages())
                <div class="mt-6">
                    {{ $products->links() }}
                </div>
            @endif
        @endif
    </div>

    {{-- نظرات دریافت‌شده --}}
    <div>
        <h2 class="text-lg font-bold mb-4">نظرات ({{ $reviews->total() }})</h2>

        @if($reviews->isEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
                <p>هنوز نظری دریافت نشده</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                @foreach($reviews as $review)
                    <div class="px-6 py-4 border-b border-gray-50 last:border-0">
                        <div class="flex items-center justify-between mb-1">
                            <span class="font-medium text-sm">{{ $review->user->username }}</span>
                            <span class="text-xs text-gray-400">{{ str_repeat('⭐', $review->rating) }}</span>
                        </div>
                        <p class="text-gray-400 text-xs mb-2">
                            درباره‌ی
                            <a href="{{ route('products.show', $review->product) }}" class="hover:underline">
                                {{ $review->product->title }}
                            </a>
                        </p>
                        <p class="text-gray-600 text-sm">{{ $review->comment }}</p>
                    </div>
                @endforeach
            </div>

            @if($reviews->hasPages())
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

@endsection
