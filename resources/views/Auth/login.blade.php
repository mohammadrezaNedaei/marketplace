@extends('layouts.app')

@section('title', 'ورود')

@section('content')
<div class="max-w-md mx-auto mt-16 bg-white p-8 rounded-2xl shadow-sm border border-gray-100">
    <h1 class="text-2xl font-bold mb-6 text-center">ورود</h1>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- نام کاربری --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">نام کاربری</label>
            <input type="text" name="username" value="{{ old('username') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- رمز عبور --}}
        <div class="mb-6">
            <label class="block text-sm font-medium mb-1">رمز عبور</label>
            <input type="password" name="password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
            ورود
        </button>

        <p class="text-center text-sm text-gray-500 mt-4">
            حساب ندارید؟
            <a href="{{ route('register') }}" class="text-black font-medium hover:underline">ثبت‌نام</a>
        </p>
    </form>
</div>
@endsection