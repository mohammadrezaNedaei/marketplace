<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'مارکت‌پلیس')</title>
    <link href="https://cdn.jsdelivr.net/gh/rastikerdar/vazirmatn@v33.003/Vazirmatn-font-face.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    <script type="text/javascript" src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    @if(session('error'))
    <script>
        alert(@json(session('error')));
    </script>
    @endif

    @if(session('success'))
    <script>
        alert(@json(session('success')));
    </script>
    @endif
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 text-gray-900 min-h-screen">

    <nav class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-xl font-bold tracking-tight">مارکت‌پلیس</a>
        <div class="flex items-center gap-4 text-sm">
            <a href="{{ route('explore') }}" class="hover:text-black text-gray-500">کاوش</a>

            @auth
            @php
            $dashboardRoute = match(Auth::user()->role) {
            'seller' => route('seller.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('buyer.dashboard'),
            };
            @endphp
            @if (Auth::user()->role != 'admin')
                <a href="{{ route('tickets.index') }}" class="hover:text-black text-gray-500 text-sm">پشتیبانی</a>
            @endif
            <a href="{{ $dashboardRoute }}" class="text-gray-500 text-sm hover:text-black">{{ Auth::user()->username }}</a>
            @if (!(Route::currentRouteName() === 'wallet.index') && Auth::user()->role != 'admin')

            <a href="{{ route('wallet.index') }}" class="hover:text-black text-gray-500 text-sm">کیف پول
                <p>{{ number_format(Auth::user()->wallet_balance) }}
                    تومان
                </p>
            </a>
            @endif
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:text-black text-sm">خروج</button>
            </form>
            @else
            <a href="{{ route('login') }}" class="hover:text-black text-gray-500">ورود</a>
            <a href="{{ route('register') }}" class="bg-black text-white px-4 py-1.5 rounded-full hover:bg-gray-800">ثبت‌نام</a>
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
