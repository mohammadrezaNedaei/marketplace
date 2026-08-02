@extends('layouts.dashboard')

@section('title', 'مدیریت کاربران')

@section('dashboard-content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">مدیریت کاربران</h1>
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

<form method="GET" action="{{ route('admin.users') }}" class="flex gap-3 mb-6">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="جستجو بر اساس نام کاربری..."
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black flex-1">
    <select name="role"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
        <option value="">همه نقش‌ها</option>
        <option value="buyer" {{ request('role') == 'buyer' ? 'selected' : '' }}>خریدار</option>
        <option value="seller" {{ request('role') == 'seller' ? 'selected' : '' }}>فروشنده</option>
        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>ادمین</option>
    </select>
    <button type="submit"
        class="bg-black text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
        جستجو
    </button>
    @if(request()->hasAny(['search', 'role']))
        <a href="{{ route('admin.users') }}"
            class="border border-gray-300 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            پاک کردن
        </a>
    @endif
</form>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="text-gray-400 border-b border-gray-100">
                <th class="text-right px-6 py-4 font-medium">نام کاربری</th>
                <th class="text-right px-6 py-4 font-medium">موبایل</th>
                <th class="text-right px-6 py-4 font-medium">نقش</th>
                <th class="text-right px-6 py-4 font-medium">تاریخ ثبت</th>
                <th class="text-right px-6 py-4 font-medium">عملیات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr class="border-b border-gray-50 last:border-0 hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium">{{ $user->username }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->phone ?? '—' }}</td>
                    <td class="px-6 py-4">
                        @php
                            $roleClass = match($user->role) {
                                'admin'  => 'bg-purple-100 text-purple-700',
                                'seller' => 'bg-blue-100 text-blue-700',
                                default  => 'bg-gray-100 text-gray-700',
                            };
                            $roleLabel = match($user->role) {
                                'admin'  => 'ادمین',
                                'seller' => 'فروشنده',
                                default  => 'خریدار',
                            };
                        @endphp
                        <span class="text-xs px-2 py-1 rounded-full {{ $roleClass }}">
                            {{ $roleLabel }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-400">{{  \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($user->created_at)) }}</td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.edit', $user) }}"
                                class="text-xs border border-gray-300 px-3 py-1 rounded-lg hover:bg-gray-50 transition">
                                ویرایش
                            </a>
                            @if($user->id !== Auth::id())
                                <form method="POST" action="{{ route('admin.users.delete', $user) }}"
                                      onsubmit="return confirm('آیا مطمئن هستید؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-xs border border-red-200 text-red-500 px-3 py-1 rounded-lg hover:bg-red-50 transition">
                                        حذف
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($users->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    @endif
</div>

@endsection
