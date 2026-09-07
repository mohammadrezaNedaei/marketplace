@extends('layouts.dashboard')

@section('title', 'ویرایش کاربر')

@section('dashboard-content')

<div class="max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-8">ویرایش کاربر</h1>

    <form method="POST" action="{{ route('admin.users.update', $user) }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">نام کاربری</label>
            <input type="text" name="username" value="{{ old('username', $user->username) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('username')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">شماره موبایل</label>
            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('phone')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">نقش</label>
            <select name="role"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                <option value="buyer"  {{ old('role', $user->role) == 'buyer'  ? 'selected' : '' }}>خریدار</option>
                <option value="seller" {{ old('role', $user->role) == 'seller' ? 'selected' : '' }}>فروشنده</option>
                <option value="admin"  {{ old('role', $user->role) == 'admin'  ? 'selected' : '' }}>ادمین</option>
            </select>
            @error('role')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">وضعیت حساب</label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                <option value="active"   {{ old('status', $user->status) == 'active'   ? 'selected' : '' }}>فعال</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
            </select>
            <p class="text-gray-400 text-xs mt-1">کاربران غیرفعال فقط به بخش تیکت پشتیبانی دسترسی دارند</p>
            @error('status')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div x-data="{ showPassword: false }" class="relative">
            <label class="block text-sm font-medium mb-1">رمز عبور جدید (اختیاری)</label>
            <input :type="showPassword ? 'text' : 'password'" name="password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 pl-10 focus:outline-none focus:ring-2 focus:ring-black">
            <button type="button" @click="showPassword = !showPassword"
                class="absolute left-3 top-9 text-gray-400 hover:text-gray-600 transition text-sm">
                <span x-show="!showPassword">👁️</span>
                <span x-show="showPassword">🙈</span>
            </button>
            <p class="text-gray-400 text-xs mt-1">اگر نمی‌خواهید تغییر دهید خالی بگذارید</p>
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit"
                class="flex-1 bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
                ذخیره تغییرات
            </button>
            <a href="{{ route('admin.users') }}"
                class="flex-1 text-center border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition">
                انصراف
            </a>
        </div>
    </form>
</div>

@endsection
