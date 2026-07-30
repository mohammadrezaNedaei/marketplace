@extends('layouts.app')

@section('title', 'ویرایش پروفایل')

@section('content')

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-8">ویرایش پروفایل</h1>

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">نام کاربری</label>
            <input type="text" name="username" value="{{ old('username', Auth::user()->username) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">شماره موبایل</label>
            <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <hr class="border-gray-100">

        <p class="text-sm font-medium text-gray-700">تغییر رمز عبور (اختیاری)</p>

        <div>
            <label class="block text-sm font-medium mb-1">رمز عبور فعلی</label>
            <input type="password" name="current_password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('current_password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">رمز عبور جدید</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">تکرار رمز عبور جدید</label>
            <input type="password" name="password_confirmation"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
        </div>

        <button type="submit"
            class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
            ذخیره تغییرات
        </button>
    </form>
</div>

@endsection
