@extends('layouts.app')

@section('title', 'خانه')

@section('content')
    <div class="relative -mx-4 px-4 overflow-hidden">

        <div class="absolute inset-0 -z-10 overflow-hidden">
            <div class="blob blob-1"></div>
            <div class="blob blob-2"></div>
        </div>

        <div class="max-w-3xl mx-auto text-center py-28">
            <p class="text-bronze text-sm tracking-widest mb-6">فضایی برای خلق و کشف</p>

            <h1 class="text-5xl md:text-6xl font-bold text-ink leading-tight mb-6">
                هر ایده‌ای، یک مقصد داره
            </h1>

            <p class="text-ink/60 text-lg leading-relaxed mb-2 max-w-xl mx-auto">
                محصولات دیجیتالتو کشف کن
            </p>
            <p class="text-ink/60 text-lg leading-relaxed mb-10 max-w-xl mx-auto">
                کار خودتو تو فضایی ساده و بی‌دغدغه به نمایش بزار
            </p>

            <a href="{{ route('explore') }}"
                class="inline-block bg-sage text-white px-10 py-4 rounded-full hover:bg-sage-light transition-colors duration-300 font-medium">
                شروع کاوش
            </a>
        </div>
    </div>
    <div class="max-w-3xl mx-auto py-16">
        <div class="flex items-center justify-center gap-16 text-center">

            <div>
                <svg class="w-6 h-6 mx-auto mb-3 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375C2.754 3.75 2.25 4.254 2.25 4.875v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                <p class="text-3xl font-bold text-ink">{{ number_format($stats['products']) }}</p>
                <p class="text-bronze text-sm mt-1">محصول</p>
            </div>

            <div class="w-px h-14 bg-sand"></div>

            <div>
                <svg class="w-6 h-6 mx-auto mb-3 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <p class="text-3xl font-bold text-ink">{{ number_format($stats['sellers']) }}</p>
                <p class="text-bronze text-sm mt-1">فروشنده</p>
            </div>

            <div class="w-px h-14 bg-sand"></div>

            <div>
                <svg class="w-6 h-6 mx-auto mb-3 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <p class="text-3xl font-bold text-ink">{{ number_format($stats['buyers']) }}</p>
                <p class="text-bronze text-sm mt-1">خریدار</p>
            </div>

        </div>
    </div>
    <div class="max-w-5xl mx-auto py-16">
        <h2 class="text-2xl font-bold text-ink text-center mb-2">دسته‌بندی‌ها</h2>
        <p class="text-bronze text-center mb-12">هرچی دنبالشی رو یک‌جا پیدا می‌کنی</p>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
            @foreach ($categories as $category)
                <a href="{{ route('explore', ['category' => $category->id]) }}"
                    class="category-card rounded-2xl p-8 text-center hover:scale-[1.02] transition-transform duration-300">
                    <div class="relative z-10">
                        <svg class="w-8 h-8 mx-auto mb-4 text-sage" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                            stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.75 3.104v5.714a2.25 2.25 0 01-.659 1.591L5 14.5M9.75 3.104c-.251.023-.501.05-.75.082m.75-.082a24.301 24.301 0 014.5 0m0 0v5.714c0 .597.237 1.17.659 1.591L19.8 15.3M14.25 3.104c.251.023.501.05.75.082M19.8 15.3l-1.57.393A9.065 9.065 0 0112 15a9.065 9.065 0 00-6.23-.693L5 14.5m14.8.8l1.402 1.402c1.232 1.232.65 3.318-1.067 3.611A48.309 48.309 0 0112 21c-2.773 0-5.491-.235-8.135-.687-1.718-.293-2.3-2.379-1.067-3.61L5 14.5" />
                        </svg>
                        <p class="font-bold text-ink mb-1">{{ $category->name }}</p>
                        <p class="text-bronze text-sm">{{ $category->products_count }} محصول</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
    <div class="max-w-6xl mx-auto py-16">
        <h2 class="text-2xl font-bold text-ink text-center mb-2">محصولات پرطرفدار</h2>
        <p class="text-bronze text-center mb-12">پست هایی که دیگران بیشتر دیده ان </p>

        @if ($featuredProducts->isEmpty())
            <p class="text-center text-bronze py-12">هنوز محصولی ثبت نشده است</p>
        @else
            <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
                @foreach ($featuredProducts as $product)
                    <a href="{{ route('products.show', $product) }}"
                        class="group block border border-sand rounded-2xl p-4 hover:shadow-lg transition-shadow duration-300">
                        <div class="rounded-2xl overflow-hidden mb-3 bg-sand">
                            <img src="{{ asset('storage/' . $product->picture_url) }}" alt="{{ $product->title }}"
                                class="w-full h-40 object-cover group-hover:scale-105 transition-transform duration-500">
                        </div>
                        <p class="text-bronze text-xs mb-1">{{ $product->category->name }}</p>
                        <p class="font-bold text-ink text-sm mb-1 line-clamp-1">{{ $product->title }}</p>
                        <p class="text-ink/70 text-sm">
                            {{ number_format($product->discount_price ?? $product->price) }} تومان
                        </p>
                    </a>
                @endforeach
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('explore') }}"
                    class="inline-block border border-sand text-ink px-8 py-3 rounded-full hover:bg-sand/30 transition-colors duration-300 font-medium text-sm">
                    مشاهده همه محصولات
                </a>
            </div>
        @endif
    </div>
    <div class="max-w-5xl mx-auto py-20">
        <h2 class="text-2xl font-bold text-ink text-center mb-16">چطور کار میکنه</h2>

        <div class="grid md:grid-cols-2 gap-16">

            <div>
                <div class="rounded-2xl overflow-hidden mb-6 h-48">
                    <img src="https://images.pexels.com/photos/4050315/pexels-photo-4050315.jpeg"
                        alt="خریدار در حال کاوش محصولات" class="w-full h-full object-cover">
                </div>
                <p class="text-sage font-medium text-sm mb-6">برای خریداران</p>
                <div class="space-y-8">
                    <div class="flex gap-4">
                        <span class="text-3xl font-bold text-sand shrink-0">۱</span>
                        <div>
                            <p class="font-bold text-ink mb-1">کاوش کن</p>
                            <p class="text-ink/60 text-sm leading-relaxed">در میان محصولات دیجیتال بگرد</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-3xl font-bold text-sand shrink-0">۲</span>
                        <div>
                            <p class="font-bold text-ink mb-1">ذخیره یا خرید کن</p>
                            <p class="text-ink/60 text-sm leading-relaxed">هرچی دوست داشتی رو ذخیره کن یا همون جا خریدتو
                                کامل کن</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-3xl font-bold text-sand shrink-0">۳</span>
                        <div>
                            <p class="font-bold text-ink mb-1">استفاده کن</p>
                            <p class="text-ink/60 text-sm leading-relaxed">فایل دیجیتالت فورا برای دانلود آماده است</p>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <div class="rounded-2xl overflow-hidden mb-6 h-48">
                    <img src="https://images.pexels.com/photos/1181677/pexels-photo-1181677.jpeg"
                        alt="فروشنده در حال ساخت محصول" class="w-full h-full object-cover">
                </div>
                <p class="text-sage font-medium text-sm mb-6">برای فروشندگان</p>
                <div class="space-y-8">
                    <div class="flex gap-4">
                        <span class="text-3xl font-bold text-sand shrink-0">۱</span>
                        <div>
                            <p class="font-bold text-ink mb-1">ثبت‌نام کن</p>
                            <p class="text-ink/60 text-sm leading-relaxed">یک حساب فروشنده بساز، در کمتر از یک
                                دقیقه
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-3xl font-bold text-sand shrink-0">۲</span>
                        <div>
                            <p class="font-bold text-ink mb-1">محصولت را معرفی کن</p>
                            <p class="text-ink/60 text-sm leading-relaxed">عکس، توضیحات و قیمت را اضافه کن</p>
                        </div>
                    </div>
                    <div class="flex gap-4">
                        <span class="text-3xl font-bold text-sand shrink-0">۳</span>
                        <div>
                            <p class="font-bold text-ink mb-1">بازدید و فروش را ببین</p>
                            <p class="text-ink/60 text-sm leading-relaxed">با آمار ساده و شفاف، رشد کارت را دنبال
                                کن
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="text-center mt-16">
            <a href="{{ route('register') }}"
                class="inline-block bg-ink text-white px-10 py-4 rounded-full hover:bg-ink/90 transition-colors duration-300 font-medium">
                همین حالا شروع کن
            </a>
        </div>
    </div>
@endsection
