@extends('layouts.app')

@section('title', 'تیکت‌های پشتیبانی')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">تیکت‌های پشتیبانی</h1>
    <a href="{{ route('admin.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت
    </a>
</div>

{{-- فیلتر وضعیت --}}
<div class="flex gap-2 mb-6">
    @foreach(['', 'open', 'answered', 'closed'] as $status)
        @php
            $label = match($status) {
                'open'     => 'باز',
                'answered' => 'پاسخ داده شده',
                'closed'   => 'بسته',
                default    => 'همه',
            };
        @endphp
        <a href="{{ route('admin.tickets', $status ? ['status' => $status] : []) }}"
            class="px-4 py-1.5 rounded-full text-sm border transition
                {{ request('status') == $status
                    ? 'bg-black text-white border-black'
                    : 'border-gray-300 hover:bg-gray-50' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

{{-- لیست تیکت‌ها --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @forelse($tickets as $ticket)
        <a href="{{ route('admin.tickets.show', $ticket) }}"
            class="flex items-center justify-between px-6 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
            <div>
                <p class="font-medium text-sm">{{ $ticket->subject ?? 'بدون موضوع' }}</p>
                <p class="text-gray-400 text-xs mt-0.5">{{ $ticket->user->username }} · {{  \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($ticket->created_at)) }}</p>
            </div>
            @php
                $statusClass = match($ticket->status) {
                    'open'     => 'bg-red-100 text-red-600',
                    'answered' => 'bg-green-100 text-green-600',
                    'closed'   => 'bg-gray-100 text-gray-500',
                };
                $statusLabel = match($ticket->status) {
                    'open'     => 'باز',
                    'answered' => 'پاسخ داده شده',
                    'closed'   => 'بسته',
                };
            @endphp
            <span class="text-xs px-2 py-1 rounded-full {{ $statusClass }}">
                {{ $statusLabel }}
            </span>
        </a>
    @empty
        <p class="text-gray-400 text-sm text-center py-12">هیچ تیکتی وجود ندارد</p>
    @endforelse

    @if($tickets->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $tickets->links() }}
        </div>
    @endif
</div>

@endsection
