@extends('layouts.dashboard')

@section('title', 'مدیریت محصولات')

@section('dashboard-content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">مدیریت محصولات</h1>
    <a href="{{ route('admin.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
        {{ session('success') }}
    </div>
@endif

{{-- فیلتر --}}
<form method="GET" action="{{ route('admin.products') }}" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="جستجو بر اساس عنوان..."
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black flex-1">
    <select name="status"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
        <option value="">همه وضعیت‌ها</option>
        <option value="active"   {{ request('status') == 'active'   ? 'selected' : '' }}>فعال</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>غیرفعال</option>
    </select>
    <button type="submit"
        class="bg-black text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
        جستجو
    </button>
    @if(request()->hasAny(['search', 'status']))
        <a href="{{ route('admin.products') }}"
            class="border border-gray-300 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            پاک کردن
        </a>
    @endif
</form>

{{-- جدول محصولات --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-400 border-b border-gray-100">
                <th class="text-right px-6 py-4 font-medium">محصول</th>
                <th class="text-right px-6 py-4 font-medium">فروشنده</th>
                <th class="text-right px-6 py-4 font-medium">دسته‌بندی</th>
                <th class="text-right px-6 py-4 font-medium">قیمت</th>
                <th class="text-right px-6 py-4 font-medium">بازدید</th>
                <th class="text-right px-6 py-4 font-medium">فروش</th>
                <th class="text-right px-6 py-4 font-medium">وضعیت</th>
                <th class="text-right px-6 py-4 font-medium">عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <img src="{{ asset('storage/' . $product->picture_url) }}"
                                 class="w-10 h-10 rounded-lg object-cover">
                            <span class="font-medium">{{ $product->title }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->seller->username }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->category->name }}</td>
                    <td class="px-6 py-4">{{ number_format($product->price) }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->views }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $product->sales_count }}</td>
                    <td class="px-6 py-4">
                        <span class="text-xs px-2 py-1 rounded-full
                            {{ $product->status === 'active'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-gray-100 text-gray-500' }}">
                            {{ $product->status === 'active' ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}"
                                class="text-xs border border-gray-300 px-3 py-1 rounded-lg hover:bg-gray-50 transition">
                                ویرایش
                            </a>
                            <form method="POST" action="{{ route('admin.products.delete', $product) }}"
                                  onsubmit="return confirm('آیا مطمئن هستید؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="text-xs border border-red-200 text-red-500 px-3 py-1 rounded-lg hover:bg-red-50 transition">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $products->links() }}
        </div>
    @endif
</div>

@endsection
