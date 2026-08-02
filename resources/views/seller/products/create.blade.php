@extends('layouts.app')

@section('title', 'افزودن محصول')

@section('content')
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">آنالیتیکس فروش</h1>
    <a href="{{ route('seller.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت به داشبورد
    </a>
</div>
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-8">افزودن محصول جدید</h1>

    <form method="POST" action="{{ route('seller.products.store') }}" enctype="multipart/form-data"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">عنوان محصول</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">دسته‌بندی</label>
            <select name="category_id"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                <option value="">انتخاب کنید</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">توضیحات</label>
            <textarea name="description" rows="4"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">{{ old('description') }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">قیمت (تومان)</label>
                <input type="number" name="price" value="{{ old('price') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">قیمت با تخفیف (اختیاری)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price') }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                @error('discount_price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">عکس محصول</label>
            <input type="file" name="picture" accept="image/*"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('picture')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">فایل دیجیتال (اختیاری)</label>
            <input type="file" name="file"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('file')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit"
            class="w-full bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
            ثبت محصول
        </button>
    </form>
</div>
@endsection
