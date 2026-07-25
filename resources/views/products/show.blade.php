@extends('layouts.app')

@section('title', $product->title)

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-8">
        <div class="md:flex">

            <div class="md:w-1/2">
                <img src="{{ asset('storage/' . $product->picture_url) }}"
                    alt="{{ $product->title }}"
                    class="w-full h-80 object-cover">
            </div>

            <div class="md:w-1/2 p-8 flex flex-col justify-between">
                <div>
                    <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $product->category->name }}
                    </span>

                    <h1 class="text-2xl font-bold mt-4 mb-2">{{ $product->title }}</h1>

                    <p class="text-sm text-gray-500 mb-1">
                        فروشنده:
                        <span class="font-medium text-gray-700">{{ $product->seller->username }}</span>
                    </p>

                    <div class="flex items-center gap-4 text-sm text-gray-400 mb-6">
                        <span>
                            {{ $product->views }} بازدید · {{ $product->sales_count }} فروش
                        </span>

                        @auth
                        @php
                        $isLiked = \App\Models\Like::where('user_id', Auth::id())
                        ->where('product_id', $product->id)
                        ->exists();
                        @endphp

                        <form method="POST" action="{{ route('products.like', $product) }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 hover:text-red-500 transition {{ $isLiked ? 'text-red-500' : '' }}">
                                <span>{{ $isLiked ? '❤️' : '🤍' }}</span>
                                <span>{{ $product->likes->count() }}</span>
                            </button>
                        </form>
                        @else
                        <span class="flex items-center gap-1">
                            🤍 {{ $product->likes->count() }}
                        </span>
                        @endauth
                    </div>

                    @if($product->description)
                    <p class="text-gray-600 text-sm leading-relaxed mb-6">
                        {{ $product->description }}
                    </p>
                    @endif
                </div>

                <div>
                    <div class="mb-4">
                        @if($product->discount_price)
                        <p class="text-gray-400 line-through text-sm">
                            {{ number_format($product->price) }} تومان
                        </p>
                        <p class="text-2xl font-bold">
                            {{ number_format($product->discount_price) }} تومان
                        </p>
                        @else
                        <p class="text-2xl font-bold">
                            {{ number_format($product->price) }} تومان
                        </p>
                        @endif
                    </div>

                    @auth
                    @if(Auth::user()->id === $product->seller_id)
                    <p class="text-sm text-gray-400 text-center">
                        امکان خرید محصول خود وجود ندارد
                    </p>
                    @elseif(Auth::user()->role === 'buyer')
                    @php
                    $alreadyBought = Auth::user()->orders
                    ->where('product_id', $product->id)
                    ->where('status', 'paid')
                    ->first();
                    @endphp

                    @if($alreadyBought)
                    <a href="{{ route('orders.show', $alreadyBought) }}"
                        class="block text-center w-full bg-green-600 text-white py-3 rounded-xl hover:bg-green-700 transition font-medium">
                        مشاهده محصول خریداری شده
                    </a>
                    @else
                    <form method="POST" action="{{ route('orders.store', $product) }}">
                        @csrf
                        <button type="submit"
                            class="w-full bg-black text-white py-3 rounded-xl hover:bg-gray-800 transition font-medium">
                            خرید محصول
                        </button>
                    </form>
                    @endif
                    {{-- دکمه ذخیره -- فقط برای خریدار --}}
                    @if(Auth::user()->role === 'buyer')
                    @php
                    $isSaved = \App\Models\Save::where('user_id', Auth::id())
                    ->where('product_id', $product->id)
                    ->exists();
                    @endphp
                    <form method="POST" action="{{ route('products.save', $product) }}" class="mt-3">
                        @csrf
                        <button type="submit"
                            class="w-full border border-gray-300 py-2 rounded-xl text-sm hover:bg-gray-50 transition">
                            {{ $isSaved ? '🔖 ذخیره شده' : '🔖 ذخیره محصول' }}
                        </button>
                    </form>
                    @endif
                    @else
                    <p class="text-sm text-gray-400 text-center">
                        فروشندگان نمی‌توانند خرید کنند
                    </p>
                    @endif
                    @else
                    <a href="{{ route('login') }}"
                        class="block text-center w-full bg-black text-white py-3 rounded-xl hover:bg-gray-800 transition font-medium">
                        برای خرید وارد شوید
                    </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">
        <h2 class="text-lg font-bold mb-6">نظرات ({{ $product->reviews->count() }})</h2>

        @auth
        @if(auth()->user()->role === 'buyer')
        <form method="POST" action="{{ route('reviews.store', $product) }}" class="mb-8">
            @csrf
            <div class="mb-3">
                <label class="block text-sm font-medium mb-1">امتیاز</label>
                <select name="rating"
                    class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <option value="5">⭐⭐⭐⭐⭐ عالی</option>
                    <option value="4">⭐⭐⭐⭐ خوب</option>
                    <option value="3">⭐⭐⭐ متوسط</option>
                    <option value="2">⭐⭐ ضعیف</option>
                    <option value="1">⭐ خیلی ضعیف</option>
                </select>
            </div>
            <textarea name="comment" rows="3" placeholder="نظر خود را بنویسید..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black mb-3"></textarea>
            <button type="submit"
                class="bg-black text-white px-6 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
                ثبت نظر
            </button>
        </form>
        @endif
        @endauth

        @forelse($product->reviews as $review)
        <div class="border-b border-gray-100 pb-6 mb-6 p-4 last:border-3 rounded-xl">
            <div class="flex items-center justify-between mb-2">
                <span class="font-medium text-sm">{{ $review->user->username }}</span>
                <span class="text-xs text-gray-400">
                    {{ str_repeat('⭐', $review->rating) }}
                </span>
            </div>
            <p class="text-gray-600 text-sm">{{ $review->comment }}</p>

            {{-- پاسخ‌ها --}}
            @foreach($review->replies as $reply)
            <div class="mr-6 mt-4 bg-gray-50 rounded-xl p-4">
                <span class="font-medium text-sm text-gray-700">{{ $reply->user->username }}</span>
                <p class="text-gray-600 text-sm mt-1">{{ $reply->comment }}</p>
            </div>
            @endforeach

            @auth
            <form method="POST" action="{{ route('reviews.reply', $review) }}" class="mr-6 mt-4 flex gap-2">
                @csrf
                <input type="text" name="comment" placeholder="پاسخ ..."
                    class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                <button type="submit"
                    class="bg-black text-white px-4 py-1.5 rounded-lg text-sm hover:bg-gray-800 transition">
                    ارسال
                </button>
            </form>
            @endauth
        </div>
        @empty
        <p class="text-gray-400 text-sm text-center py-8">هنوز نظری ثبت نشده</p>
        @endforelse
    </div>

</div>

@endsection