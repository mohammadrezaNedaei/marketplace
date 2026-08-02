@extends('layouts.dashboard')

@section('title', 'داشبورد ادمین')

@section('dashboard-content')

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold mb-8">پنل ادمین</h1>
        <a href="{{ route('profile.edit') }}"
            class="border border-gray-300 px-5 py-2 mr-2 rounded-full text-sm hover:bg-gray-50 transition">
            ویرایش پروفایل
        </a>

    </div>


    <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-gray-400 text-xs mb-1">کل کاربران</p>
            <p class="text-2xl font-bold">{{ number_format($totalUsers) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-gray-400 text-xs mb-1">فروشندگان</p>
            <p class="text-2xl font-bold">{{ number_format($totalSellers) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-gray-400 text-xs mb-1">خریداران</p>
            <p class="text-2xl font-bold">{{ number_format($totalBuyers) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-gray-400 text-xs mb-1">محصولات فعال</p>
            <p class="text-2xl font-bold">{{ number_format($totalProducts) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            <p class="text-gray-400 text-xs mb-1">تیکت‌های باز</p>
            <p class="text-2xl font-bold {{ $openTickets === 0 ? 'text-green-500' : 'text-red-500' }}">
                {{ number_format($openTickets) }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
        <h2 class="font-bold mb-4">آخرین فعالیت‌ها</h2>

        @if ($recentActivities->isEmpty())
            <p class="text-gray-400 text-sm text-center py-4">هنوز فعالیتی ثبت نشده</p>
        @else
            <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                @foreach ($recentActivities as $activity)
                    <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0 pl-8">
                        <div class="flex items-center gap-3">
                            <span class="text-xl">{{ $activity['icon'] }}</span>
                            <span class="text-sm text-gray-700">{{ $activity['text'] }}</span>
                        </div>
                        <span class="text-xs text-gray-400 shrink-0 mr-4">
                            {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($activity['created_at']))->ago() }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <a href="{{ route('admin.users') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
            <h2 class="font-bold mb-1">مدیریت کاربران</h2>
            <p class="text-gray-400 text-sm">مشاهده، ویرایش و حذف کاربران</p>
        </a>
        <a href="{{ route('admin.tickets') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
            <h2 class="font-bold mb-1">تیکت‌های پشتیبانی</h2>
            <p class="text-gray-400 text-sm">پاسخ به سوالات و مشکلات کاربران</p>
        </a>
        <a href="{{ route('admin.products') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
            <h2 class="font-bold mb-1">مدیریت محصولات</h2>
            <p class="text-gray-400 text-sm">مشاهده، ویرایش و حذف محصولات</p>
        </a>
        <a href="{{ route('admin.withdrawals') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
            <h2 class="font-bold mb-1">درخواست‌های برداشت</h2>
            <p class="text-gray-400 text-sm">تایید یا رد درخواست‌های برداشت فروشندگان</p>
        </a>
        <a href="{{ route('admin.orders') }}"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 hover:shadow-md transition">
            <h2 class="font-bold mb-1">سفارشات</h2>
            <p class="text-gray-400 text-sm">مشاهده و ویرایش سفارشات</p>
        </a>
    </div>

@endsection
