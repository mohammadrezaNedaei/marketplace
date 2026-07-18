@extends('layouts.app')

@section('title', 'تیکت پشتیبانی')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">{{ $ticket->subject ?? 'بدون موضوع' }}</h1>
        <a href="{{ route('admin.tickets') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- اطلاعات تیکت --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm font-medium">{{ $ticket->user->username }}</p>
                <p class="text-gray-400 text-xs">{{ $ticket->created_at }}</p>
            </div>
            {{-- تغییر وضعیت --}}
            <form method="POST" action="{{ route('admin.tickets.status', $ticket) }}" class="flex gap-2 items-center">
                @csrf
                @method('PUT')
                <select name="status"
                    class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-black">
                    <option value="open"     {{ $ticket->status == 'open'     ? 'selected' : '' }}>باز</option>
                    <option value="answered" {{ $ticket->status == 'answered' ? 'selected' : '' }}>پاسخ داده شده</option>
                    <option value="closed"   {{ $ticket->status == 'closed'   ? 'selected' : '' }}>بسته</option>
                </select>
                <button type="submit"
                    class="bg-black text-white px-3 py-1.5 rounded-lg text-sm hover:bg-gray-800 transition">
                    ذخیره
                </button>
            </form>
        </div>
    </div>

    {{-- پیام‌ها --}}
    <div class="space-y-4 mb-6">
        @foreach($ticket->messages as $message)
            @php
                $isAdmin = $message->sender->role === 'admin';
            @endphp
            <div class="flex {{ $isAdmin ? 'justify-start' : 'justify-end' }}">
                <div class="max-w-sm {{ $isAdmin ? 'bg-gray-100' : 'bg-black text-white' }} rounded-2xl px-5 py-3">
                    <p class="text-xs font-medium mb-1 {{ $isAdmin ? 'text-gray-500' : 'text-gray-300' }}">
                        {{ $message->sender->username }}
                    </p>
                    <p class="text-sm">{{ $message->message }}</p>
                    <p class="text-xs mt-1 {{ $isAdmin ? 'text-gray-400' : 'text-gray-400' }}">
                        {{ $message->created_at }}
                    </p>
                </div>
            </div>
        @endforeach

        @if($ticket->messages->isEmpty())
            <p class="text-center text-gray-400 text-sm py-8">هنوز پیامی ارسال نشده</p>
        @endif
    </div>

    {{-- فرم پاسخ --}}
    @if($ticket->status !== 'closed')
        <form method="POST" action="{{ route('admin.tickets.reply', $ticket) }}"
              class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @csrf
            <label class="block text-sm font-medium mb-2">پاسخ شما</label>
            <textarea name="message" rows="4" placeholder="پاسخ خود را بنویسید..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black mb-4"></textarea>
            @error('message')
                <p class="text-red-500 text-xs mb-3">{{ $message }}</p>
            @enderror
            <button type="submit"
                class="bg-black text-white px-6 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
                ارسال پاسخ
            </button>
        </form>
    @else
        <div class="bg-gray-50 rounded-2xl p-6 text-center text-gray-400 text-sm">
            این تیکت بسته شده است
        </div>
    @endif

</div>

@endsection