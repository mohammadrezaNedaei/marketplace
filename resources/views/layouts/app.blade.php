<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'مارکت‌پلیس')</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">

    @if (session('success') || session('error') || $errors->any())
        <div id="toast-container" class="fixed bottom-6 left-6 z-[9999] w-[min(400px,calc(100vw-2rem))] space-y-3"
            aria-live="polite">
            @if (session('success'))
                <div class="group relative flex items-start gap-3 overflow-hidden rounded-2xl border border-emerald-200/70 bg-white/95 p-4 pr-5 shadow-[0_12px_40px_rgba(0,0,0,0.12)] backdrop-blur-xl animate-[toast-in_0.45s_cubic-bezier(0.16,1,0.3,1)_both]"
                    role="status">
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-gray-900">موفق</p>
                        <p class="mt-0.5 text-sm leading-6 text-gray-500">
                            {{ session('success') }}
                        </p>
                    </div>

                    <button type="button" onclick="this.parentElement.remove()"
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-all duration-200 hover:bg-gray-100 hover:text-gray-700 active:scale-90"
                        aria-label="بستن">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>

                    <div class="absolute bottom-0 right-0 left-0 h-0.5 bg-emerald-500/20">
                        <div class="h-full origin-right bg-emerald-500 animate-[toast-progress_4s_linear_forwards]">
                        </div>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="group relative flex items-start gap-3 overflow-hidden rounded-2xl border border-red-200/70 bg-white/95 p-4 pr-5 shadow-[0_12px_40px_rgba(0,0,0,0.12)] backdrop-blur-xl animate-[toast-in_0.45s_cubic-bezier(0.16,1,0.3,1)_both]"
                    role="alert">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75-3L13.7 3.8a2 2 0 00-3.4 0z" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-gray-900">خطا</p>
                        <p class="mt-0.5 text-sm leading-6 text-gray-500">
                            {{ session('error') }}
                        </p>
                    </div>

                    <button type="button" onclick="this.parentElement.remove()"
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-all duration-200 hover:bg-gray-100 hover:text-gray-700 active:scale-90"
                        aria-label="بستن">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>

                    <div class="absolute bottom-0 right-0 left-0 h-0.5 bg-red-500/20">
                        <div class="h-full origin-right bg-red-500 animate-[toast-progress_4s_linear_forwards]"></div>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="group relative flex items-start gap-3 overflow-hidden rounded-2xl border border-red-200/70 bg-white/95 p-4 pr-5 shadow-[0_12px_40px_rgba(0,0,0,0.12)] backdrop-blur-xl animate-[toast-in_0.45s_cubic-bezier(0.16,1,0.3,1)_both]"
                    role="alert">
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-red-50 text-red-600">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v4m0 4h.01M10.3 3.8L2.9 17a2 2 0 001.75 3h14.7a2 2 0 001.75 3h-14.7a2 2 0 01-1.75-3l7.4-13.2a2 2 0 013.4 0z" />
                        </svg>
                    </div>

                    <div class="min-w-0 flex-1 pt-0.5">
                        <p class="text-sm font-bold text-gray-900">خطا در اطلاعات</p>

                        <div class="mt-0.5 space-y-1 text-sm leading-6 text-gray-500">
                            @foreach ($errors->all() as $error)
                                <p>{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" onclick="this.parentElement.remove()"
                        class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-gray-400 transition-all duration-200 hover:bg-gray-100 hover:text-gray-700 active:scale-90"
                        aria-label="بستن">
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6l12 12M18 6L6 18" />
                        </svg>
                    </button>

                    <div class="absolute bottom-0 right-0 left-0 h-0.5 bg-red-500/20">
                        <div class="h-full origin-right bg-red-500 animate-[toast-progress_4s_linear_forwards]"></div>
                    </div>
                </div>
            @endif
        </div>

        <style>
            @keyframes toast-in {
                from {
                    opacity: 0;
                    transform: translateX(-24px) scale(0.96);
                }

                to {
                    opacity: 1;
                    transform: translateX(0) scale(1);
                }
            }

            @keyframes toast-progress {
                from {
                    transform: scaleX(1);
                }

                to {
                    transform: scaleX(0);
                }
            }
        </style>

        <script>
            document.querySelectorAll('#toast-container > div').forEach((toast) => {
                setTimeout(() => {
                    toast.style.transition = 'opacity 300ms ease, transform 300ms ease';
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateX(-20px) scale(0.96)';

                    setTimeout(() => toast.remove(), 300);
                }, 4000);
            });
        </script>
    @endif


    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">مارکت‌پلیس</a>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('explore') }}" class="hover:text-black text-gray-500">کاوش</a>

            @auth
                @php
                    $dashboardRoute = match (Auth::user()->role) {
                        'seller' => route('seller.dashboard'),
                        'admin' => route('admin.dashboard'),
                        default => route('buyer.dashboard'),
                    };
                @endphp

                @if (Auth::user()->role != 'admin')
                    <a href="{{ route('tickets.index') }}" class="hover:text-black text-gray-500 text-sm">
                        پشتیبانی
                    </a>

                    {{-- Followers / Following --}}
                    <a href="{{ route('follows.index') }}" class="hover:text-black text-gray-500 text-sm">
                        {{ Auth::user()->role === 'seller' ? 'دنبال‌کننده‌ها' : 'دنبال‌شده‌ها' }}
                    </a>
                @endif

                <a href="{{ $dashboardRoute }}" class="text-gray-500 text-sm hover:text-black">
                    {{ Auth::user()->username }}
                </a>

                @if (!(Route::currentRouteName() === 'wallet.index') && Auth::user()->role != 'admin')
                    <a href="{{ route('wallet.index') }}" class="hover:text-black text-gray-500 text-sm">
                        کیف پول
                        <p>
                            {{ number_format(Auth::user()->wallet_balance) }}
                            تومان
                        </p>
                    </a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-gray-500 hover:text-black text-sm">
                        خروج
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="hover:text-black text-gray-500">
                    ورود
                </a>

                <a href="{{ route('register') }}" class="bg-black text-white px-4 py-1.5 rounded-full hover:bg-gray-800">
                    ثبت‌نام
                </a>
            @endauth
        </div>
    </nav>

    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <footer class="text-center text-xs text-gray-400 py-6 border-t border-gray-100 mt-12">
        © {{ date('Y') }} مارکت‌پلیس
    </footer>

</body>

</html>
