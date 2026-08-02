@extends('layouts.dashboard')

@section('title', 'مدیریت سفارشات')

@section('dashboard-content')

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">مدیریت سفارشات</h1>
        <a href="{{ route('admin.dashboard') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت
        </a>
    </div>

    <form method="GET" action="{{ route('admin.orders') }}" class="flex flex-wrap gap-3 mb-6">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="جستجو بر اساس محصول یا کاربر..."
            class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black flex-1 min-w-48">

        <select name="status"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
            <option value="">همه وضعیت‌ها</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>در انتظار</option>
            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>پرداخت شده</option>
            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تحویل شده</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>لغو شده</option>
        </select>

        <input type="text" name="from_date" value="{{ request('from_date') }}" data-jdp dir="ltr" readonly
            autocomplete="off" placeholder="از تاریخ"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

        <input type="text" name="to_date" value="{{ request('to_date') }}" data-jdp dir="ltr" readonly
            autocomplete="off" placeholder="تا تاریخ"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

        <button type="submit" class="bg-black text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
            اعمال فیلتر
        </button>
        @if (request()->hasAny(['search', 'status', 'from_date', 'to_date']))
            <a href="{{ route('admin.orders') }}"
                class="border border-gray-300 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                پاک کردن
            </a>
        @endif
    </form>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-gray-400 border-b border-gray-100">
                    <th class="text-right px-6 py-4 font-medium">محصول</th>
                    <th class="text-right px-6 py-4 font-medium">خریدار</th>
                    <th class="text-right px-6 py-4 font-medium">مبلغ</th>
                    <th class="text-right px-6 py-4 font-medium">تاریخ</th>
                    <th class="text-right px-6 py-4 font-medium">وضعیت</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium">{{ $order->product->title ?? '—' }}</td>
                        <td class="px-6 py-4 text-gray-500">{{ $order->user->username ?? '—' }}</td>
                        <td class="px-6 py-4">{{ number_format($order->amount) }} تومان</td>
                        <td class="px-6 py-4 text-gray-400">
                            {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($order->created_at))->format('Y/m/d H:i') }}
                        </td>
                        <td class="px-6 py-4">
                            <form method="POST" action="{{ route('admin.orders.status', $order) }}"
                                class="flex items-center gap-2">
                                @csrf
                                @method('PUT')
                                <select name="status" onchange="this.form.submit()"
                                    class="border border-gray-300 rounded-lg px-2 py-1 text-xs focus:outline-none focus:ring-2 focus:ring-black">
                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>در انتظار
                                    </option>
                                    <option value="paid" {{ $order->status == 'paid' ? 'selected' : '' }}>پرداخت شده
                                    </option>
                                    <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تحویل
                                        شده</option>
                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>لغو شده
                                    </option>
                                </select>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        @if ($orders->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $orders->links() }}
            </div>
        @endif
    </div>

    <script>
        jalaliDatepicker.startWatch({
            persianDigits: true,
            autoHide: true,
            autoReadOnlyInput: true
        });
    </script>

@endsection
