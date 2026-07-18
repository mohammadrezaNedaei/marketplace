@extends('layouts.app')

@section('title', 'ویرایش محصول')

@section('content')

<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-8">ویرایش محصول</h1>

    <form method="POST" action="{{ route('admin.products.update', $product) }}"
          class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">عنوان</label>
            <input type="text" name="title" value="{{ old('title', $product->title) }}"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
            @error('title')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">دسته‌بندی</label>
            <select name="category_id"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">قیمت (تومان)</label>
                <input type="number" name="price" value="{{ old('price', $product->price) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                @error('price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">قیمت با تخفیف (اختیاری)</label>
                <input type="number" name="discount_price" value="{{ old('discount_price', $product->discount_price) }}"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                @error('discount_price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">وضعیت</label>
            <select name="status"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-black">
                <option value="active"   {{ old('status', $product->status) == 'active'   ? 'selected' : '' }}>فعال</option>
                <option value="inactive" {{ old('status', $product->status) == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium mb-2">عکس فعلی</label>
            <img src="{{ asset('storage/' . $product->picture_url) }}"
                 class="w-32 h-32 object-cover rounded-xl">
        </div>

        <div class="flex gap-4">
            <button type="submit"
                class="flex-1 bg-black text-white py-2 rounded-lg hover:bg-gray-800 transition">
                ذخیره تغییرات
            </button>
            <a href="{{ route('admin.products') }}"
                class="flex-1 text-center border border-gray-300 py-2 rounded-lg hover:bg-gray-50 transition">
                انصراف
            </a>
        </div>
    </form>
</div>

@endsection