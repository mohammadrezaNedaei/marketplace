@extends('layouts.app')

@section('title', 'تیکت جدید')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-8">ارسال تیکت جدید</h1>

    <form method="POST" action="{{ route('tickets.store') }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">موضوع</label>
            <input type="text" name="subject" value="{{ old('subject') }}"
                placeholder="موضوع مشکل یا سوال خود را بنویسید"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('subject')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">توضیحات</label>
            <textarea name="message" rows="6"
                placeholder="مشکل یا سوال خود را با جزئیات بنویسید..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">{{ old('message') }}</textarea>
            @error('message')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit"
                class="flex-1 bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
                ارسال تیکت
            </button>
            <a href="{{ route('tickets.index') }}"
                class="flex-1 text-center border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition">
                انصراف
            </a>
        </div>
    </form>
</div>

@endsection