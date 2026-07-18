@extends('layouts.app')

@section('title', 'تیکت‌های من')

@section('content')

<div class="max-w-2xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">تیکت‌های پشتیبانی</h1>
        <a href="{{ route('tickets.create') }}"
            class="bg-black text-white px-5 py-2 rounded-full text-sm hover:bg-gray-800 transition">
            + تیکت جدید
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($tickets->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
            <p class="mb-3">هنوز تیکتی ارسال نکرده‌اید</p>
            <a href="{{ route('tickets.create') }}" class="text-black underline text-sm">
                اولین تیکت را ارسال کنید
            </a>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            @foreach($tickets as $ticket)
                <a href="{{ route('tickets.show', $ticket) }}"
                    class="flex items-center justify-between px-6 py-4 border-b border-gray-50 last:border-0 hover:bg-gray-50 transition">
                    <div>
                        <p class="font-medium text-sm">{{ $ticket->subject }}</p>
                        <p class="text-gray-400 text-xs mt-0.5">{{ $ticket->created_at }}</p>
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
            @endforeach
        </div>
    @endif
</div>

@endsection