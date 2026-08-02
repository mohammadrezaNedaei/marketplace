@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex gap-8">


        <aside class="w-64 shrink-0">

            <div class="sticky top-6 bg-white border border-gray-100 rounded-2xl shadow-sm p-5">

                <h2 class="font-bold text-lg mb-6">
                    پنل کاربری
                </h2>

                <div class="space-y-2">

                    @if(auth()->user()->role === 'buyer')
                        <a href="{{ route('buyer.dashboard') }}"
                           class="block rounded-xl px-4 py-2 transition
                           {{ request()->routeIs('buyer.*') ? 'bg-black text-white' : 'hover:bg-gray-100' }}">
                            داشبورد خریدار
                        </a>

                        <a href="{{ route('buyer.purchases') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            خریدهای من
                        </a>

                        <a href="{{ route('buyer.saves') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            ذخیره‌ها
                        </a>

                        <a href="{{ route('buyer.payments') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            پرداخت‌ها
                        </a>
                    @endif

                    @if(auth()->user()->role === 'seller')
                        <hr>

                        <a href="{{ route('seller.dashboard') }}"
                           class="block rounded-xl px-4 py-2 transition
                           {{ request()->routeIs('seller.*') ? 'bg-black text-white' : 'hover:bg-gray-100' }}">
                            داشبورد فروشنده
                        </a>

                        <a href="{{ route('seller.products.create') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            افزودن محصول
                        </a>

                        <a href="{{ route('seller.analytics') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            آنالیتیکس
                        </a>
                    @endif

                    @if(auth()->user()->role === 'admin')
                        <hr>

                        <a href="{{ route('admin.dashboard') }}"
                           class="block rounded-xl px-4 py-2 transition
                           {{ request()->routeIs('admin.*') ? 'bg-black text-white' : 'hover:bg-gray-100' }}">
                            داشبورد ادمین
                        </a>

                        <a href="{{ route('admin.users') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            کاربران
                        </a>

                        <a href="{{ route('admin.products') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            محصولات
                        </a>

                        <a href="{{ route('admin.categories') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            دسته بندی‌ ها
                        </a>

                        <a href="{{ route('admin.orders') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            سفارشات
                        </a>

                        <a href="{{ route('admin.withdrawals') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            درخواست های برداشت‌
                        </a>

                        <a href="{{ route('admin.tickets') }}"
                           class="block rounded-xl px-4 py-2 transition hover:bg-gray-100">
                            تیکت‌ها
                        </a>
                    @endif

                    <hr>

                    <a href="{{ route('profile.edit') }}"
                       class="block rounded-xl px-4 py-2 hover:bg-gray-100 transition">
                        ویرایش پروفایل
                    </a>

                </div>

            </div>

        </aside>

        <main class="flex-1">
            @yield('dashboard-content')
        </main>

    </div>
</div>
@endsection
