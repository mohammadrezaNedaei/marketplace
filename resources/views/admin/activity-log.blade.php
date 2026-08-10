@extends('layouts.dashboard')

@section('title', 'لاگ فعالیت‌ها')

@section('dashboard-content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">لاگ فعالیت‌های پلتفرم</h1>
    <a href="{{ route('admin.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت
    </a>
</div>

<form method="GET" action="{{ route('admin.activity-log') }}" class="flex flex-wrap gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="جستجو..."
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black flex-1 min-w-48">

    <select name="type" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
        <option value="">همه انواع</option>
        <option value="user"       {{ request('type') == 'user'       ? 'selected' : '' }}>ثبت‌نام</option>
        <option value="order"      {{ request('type') == 'order'      ? 'selected' : '' }}>سفارش</option>
        <option value="product"    {{ request('type') == 'product'    ? 'selected' : '' }}>محصول</option>
        <option value="ticket"     {{ request('type') == 'ticket'     ? 'selected' : '' }}>تیکت</option>
        <option value="review"     {{ request('type') == 'review'     ? 'selected' : '' }}>نظر</option>
        <option value="withdrawal" {{ request('type') == 'withdrawal' ? 'selected' : '' }}>برداشت</option>
        <option value="deposit"    {{ request('type') == 'deposit'    ? 'selected' : '' }}>شارژ کیف پول</option>
    </select>

    <input type="text" name="from_date" value="{{ request('from_date') }}"
        data-jdp dir="ltr" readonly autocomplete="off" placeholder="از تاریخ"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

    <input type="text" name="to_date" value="{{ request('to_date') }}"
        data-jdp dir="ltr" readonly autocomplete="off" placeholder="تا تاریخ"
        class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

    <button type="submit" class="bg-black text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
        اعمال فیلتر
    </button>
    @if(request()->hasAny(['search', 'type', 'from_date', 'to_date']))
        <a href="{{ route('admin.activity-log') }}" class="border border-gray-300 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            پاک کردن
        </a>
    @endif
</form>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @php
        $icons = [
            'user' => '👤', 'order' => '🛍', 'product' => '📦',
            'ticket' => '🎫', 'review' => '⭐', 'withdrawal' => '💸', 'deposit' => '💰',
        ];
        $labels = [
            'user' => 'ثبت‌نام کرد', 'order' => 'سفارش ثبت کرد', 'product' => 'محصول اضافه کرد',
            'ticket' => 'تیکت باز کرد', 'review' => 'نظر ثبت کرد',
            'withdrawal' => 'درخواست برداشت داد', 'deposit' => 'کیف پول شارژ کرد',
        ];
    @endphp

    @forelse($activityLog as $event)
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 last:border-0">
            <div class="flex items-center gap-3">
                <span class="text-xl">{{ $icons[$event->type] ?? '•' }}</span>
                <span class="text-sm text-gray-700">
                    {{ $event->actor }} {{ $labels[$event->type] ?? '' }}
                    @if($event->subject)
                        — {{ $event->subject }}
                    @endif
                </span>
            </div>
            <span class="text-xs text-gray-400 shrink-0">
                {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($event->created_at))->format('Y/m/d H:i') }}
            </span>
        </div>
    @empty
        <p class="text-gray-400 text-sm text-center py-12">فعالیتی یافت نشد</p>
    @endforelse

    @if($activityLog->hasPages())
        <div class="px-6 border-t border-gray-100">
            {{ $activityLog->links() }}
        </div>
    @endif
</div>

<script>
    jalaliDatepicker.startWatch({ persianDigits: true, autoHide: true, autoReadOnlyInput: true });
</script>

@endsection
