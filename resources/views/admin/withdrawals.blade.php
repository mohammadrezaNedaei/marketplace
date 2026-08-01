@extends('layouts.app')

@section('title', 'درخواست‌های برداشت')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">درخواست‌های برداشت</h1>
        <a href="{{ route('admin.dashboard') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت
        </a>
    </div>

    <div class="flex gap-2 mb-6">
        @foreach (['', 'pending', 'approved', 'rejected'] as $status)
            @php
                $label = match ($status) {
                    'pending' => 'در انتظار',
                    'approved' => 'تایید شده',
                    'rejected' => 'رد شده',
                    default => 'همه',
                };
            @endphp
            <a href="{{ route('admin.withdrawals', $status ? ['status' => $status] : []) }}"
                class="px-4 py-1.5 rounded-full text-sm border transition
                {{ request('status') == $status ? 'bg-black text-white border-black' : 'border-gray-300 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>
    <form method="GET" action="{{ route('admin.withdrawals') }}" class="flex flex-wrap gap-3 mb-6 items-center">
        @if (request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif

        <input type="text" name="from_date" value="{{ request('from_date') }}" data-jdp dir="ltr" readonly
            autocomplete="off" placeholder="از تاریخ"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

        <input type="text" name="to_date" value="{{ request('to_date') }}" data-jdp dir="ltr" readonly
            autocomplete="off" placeholder="تا تاریخ"
            class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

        <button type="submit" class="bg-black text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
            اعمال فیلتر
        </button>

        @if (request()->hasAny(['from_date', 'to_date']))
            <a href="{{ route('admin.withdrawals', request('status') ? ['status' => request('status')] : []) }}"
                class="border border-gray-300 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                پاک کردن تاریخ
            </a>
        @endif
    </form>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @forelse($withdrawals as $withdrawal)
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-50 last:border-0">
                <div>
                    <p class="font-medium text-sm">{{ $withdrawal->user->username }}</p>
                    <p class="text-gray-400 text-xs mt-0.5">{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($withdrawal->created_at)) }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <span class="font-bold text-sm">{{ number_format($withdrawal->amount) }} تومان</span>

                    @php
                        $statusClass = match ($withdrawal->status) {
                            'pending' => 'bg-yellow-100 text-yellow-700',
                            'approved' => 'bg-green-100 text-green-700',
                            'rejected' => 'bg-red-100 text-red-700',
                        };
                        $statusLabel = match ($withdrawal->status) {
                            'pending' => 'در انتظار',
                            'approved' => 'تایید شده',
                            'rejected' => 'رد شده',
                        };
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>

                    @if ($withdrawal->status === 'pending')
                        <div class="flex gap-2">
                            <form method="POST" action="{{ route('admin.withdrawals.approve', $withdrawal) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="text-xs bg-black text-white px-3 py-1.5 rounded-lg hover:bg-gray-800 transition">
                                    تایید
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.withdrawals.reject', $withdrawal) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="text-xs border border-red-200 text-red-500 px-3 py-1.5 rounded-lg hover:bg-red-50 transition">
                                    رد
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <p class="text-gray-400 text-sm text-center py-12">هیچ درخواستی وجود ندارد</p>
        @endforelse

        @if ($withdrawals->hasPages())
            <div class="px-6 py-4 border-t border-gray-100">
                {{ $withdrawals->links() }}
            </div>
        @endif
    </div>
    <script>
        jalaliDatepicker.startWatch({
            persianDigits: true,
            autoHide: true,
            autoReadOnlyInput: true,
        });
    </script>
@endsection
