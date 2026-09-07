@extends('layouts.dashboard')

@section('title', $title)

@section('dashboard-content')
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold">{{ $title }}</h1>
        </div>
        <span class="bg-gray-100 text-gray-600 text-sm px-4 py-2 rounded-full">{{ number_format($items->total()) }}
            نفر</span>
    </div>
    @if ($items->isEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-14 text-center">
            <div class="text-4xl mb-4">♡</div>
            <p class="text-gray-500">
                {{ $mode === 'following' ? 'هنوز فروشگاهی را دنبال نکرده‌اید.' : 'هنوز کسی فروشگاه شما را دنبال نکرده است.' }}
            </p>
            @if ($mode === 'following')
                <a href="{{ route('explore') }}" class="inline-block mt-4 text-sm underline">مشاهده محصولات</a>
            @endif
        </div>
    @else
        <div class="flex flex-col gap-4">
            @foreach ($items as $user)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                    <div class="flex items-center gap-4">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->username) . '&size=96' }}"
                            class="w-16 h-16 rounded-full object-cover shrink-0" alt="{{ $user->username }}">
                        <div class="min-w-0 flex-1">
                            <h2 class="font-bold truncate">{{ $user->username }}</h2>
                            @if ($mode === 'following')
                                <p class="text-xs text-gray-400 mt-1">{{ number_format($user->followers_count) }}
                                دنبال‌کننده</p>@else<p class="text-xs text-gray-400 mt-1">دنبال‌کننده فروشگاه شما</p>
                            @endif
                        </div>
                    </div>
                    @if ($mode === 'following')
                        <div class="flex items-center gap-3 mt-5">
                            <a href="{{ route('sellers.show', $user) }}"
                                class="flex-1 text-center border border-gray-300 rounded-xl py-2 text-sm
                   hover:bg-gray-50 transition">
                                مشاهده فروشگاه
                            </a>

                            <form method="POST" action="{{ route('sellers.follow', $user) }}" class="flex-1">
                                @csrf

                                <button type="submit"
                                    class="w-full text-center border border-gray-300 rounded-xl py-2 text-sm
                       hover:bg-gray-50 transition">
                                    دنبال نکردن
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        @if ($items->hasPages())
            <div class="mt-6">{{ $items->links() }}</div>
        @endif
    @endif
@endsection
