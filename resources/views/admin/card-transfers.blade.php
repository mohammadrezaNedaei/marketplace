@extends('layouts.app')

@section('title', 'درخواست‌های کارت به کارت')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">درخواست‌های کارت به کارت</h1>
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
            <a href="{{ route('admin.card-transfers', $status ? ['status' => $status] : []) }}"
                class="px-4 py-1.5 rounded-full text-sm border transition
                {{ request('status') == $status ? 'bg-black text-white border-black' : 'border-gray-300 hover:bg-gray-50' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="space-y-4">
        @forelse($cardTransfers as $transfer)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6" x-data="{ showImage: false }">
                <div class="flex flex-col md:flex-row items-start gap-6">

                    <div class="w-full md:w-48 shrink-0">
                        <img src="{{ asset('storage/' . $transfer->receipt_image) }}" @click="showImage = true"
                            class="w-full h-48 rounded-xl object-cover border border-gray-200 cursor-pointer hover:opacity-90 transition">
                        <p class="text-xs text-gray-400 text-center mt-2">برای بزرگنمایی کلیک کنید</p>
                    </div>

                    <div class="flex-1 w-full">
                        <div class="flex items-center justify-between mb-2">
                            <p class="font-medium text-sm">{{ $transfer->user->username }}</p>
                            @php
                                $statusClass = match ($transfer->status) {
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'approved' => 'bg-green-100 text-green-700',
                                    'rejected' => 'bg-red-100 text-red-700',
                                };
                                $statusLabel = match ($transfer->status) {
                                    'pending' => 'در انتظار',
                                    'approved' => 'تایید شده',
                                    'rejected' => 'رد شده',
                                };
                            @endphp
                            <span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">
                                {{ $statusLabel }}
                            </span>
                        </div>

                        <p class="font-bold text-lg mb-1">{{ number_format($transfer->amount) }} تومان</p>

                        @if ($transfer->tracking_code)
                            <p class="text-gray-400 text-xs mb-1">کد پیگیری: {{ $transfer->tracking_code }}</p>
                        @endif

                        <p class="text-gray-400 text-xs mb-3">
                            {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($transfer->created_at))->format('Y/m/d H:i') }}
                        </p>

                        @if ($transfer->admin_note)
                            <p class="text-red-500 text-xs mb-3">یادداشت: {{ $transfer->admin_note }}</p>
                        @endif

                        @if ($transfer->status === 'pending')
                            <div class="flex gap-2 items-center" x-data="{ showReject: false }">
                                <form method="POST" action="{{ route('admin.card-transfers.approve', $transfer) }}">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit"
                                        class="text-xs bg-black text-white px-4 py-1.5 rounded-lg hover:bg-gray-800 transition">
                                        تایید
                                    </button>
                                </form>

                                <button @click="showReject = !showReject"
                                    class="text-xs border border-red-200 text-red-500 px-4 py-1.5 rounded-lg hover:bg-red-50 transition">
                                    رد کردن
                                </button>

                                <div x-show="showReject" x-cloak class="w-full mt-2">
                                    <form method="POST" action="{{ route('admin.card-transfers.reject', $transfer) }}"
                                        class="flex gap-2">
                                        @csrf
                                        @method('PUT')
                                        <input type="text" name="admin_note" placeholder="دلیل رد (اختیاری)"
                                            class="flex-1 border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                                        <button type="submit"
                                            class="text-xs bg-red-500 text-white px-4 py-1.5 rounded-lg hover:bg-red-600 transition">
                                            ثبت رد
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div x-show="showImage" x-cloak @click="showImage = false" @keydown.escape.window="showImage = false"
                    class="fixed inset-0 bg-black/80 z-50 flex items-center justify-center p-4 cursor-pointer">
                    <img src="{{ asset('storage/' . $transfer->receipt_image) }}" @click.stop
                        class="max-w-full max-h-full rounded-xl shadow-2xl">
                    <button @click="showImage = false"
                        class="absolute top-6 left-6 text-white text-3xl hover:opacity-70 transition">
                        ✕
                    </button>
                </div>
            </div>
        @empty
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
                <p>هیچ درخواستی وجود ندارد</p>
            </div>
        @endforelse
    </div>

    @if ($cardTransfers->hasPages())
        <div class="mt-6">
            {{ $cardTransfers->links() }}
        </div>
    @endif

@endsection
