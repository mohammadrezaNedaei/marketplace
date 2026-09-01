@extends('layouts.app')

@section('title', 'ویرایش فروشگاه')

@section('content')

<div class="max-w-lg mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">ویرایش فروشگاه</h1>
        <a href="{{ route('sellers.show', Auth::user()) }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            مشاهده فروشگاه
        </a>
    </div>

    <form method="POST" action="{{ route('seller.profile.update') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT')

        <div class="text-center">
            <img src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name=' . Auth::user()->username . '&size=128' }}"
                 class="w-28 h-28 rounded-full object-cover mx-auto mb-4">
            <label class="block text-sm font-medium mb-1">تصویر فروشگاه</label>
            <input type="file" name="avatar" accept="image/*" class="text-sm mx-auto">
            @error('avatar')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">درباره فروشگاه</label>
            <textarea name="bio" rows="4" placeholder="چند جمله درباره خودتان یا محصولاتتان بنویسید..."
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">{{ old('bio', Auth::user()->bio) }}</textarea>
            @error('bio')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
            ذخیره تغییرات
        </button>
    </form>
</div>

@endsection
