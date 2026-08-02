@extends('layouts.dashboard')

@section('title', 'آنالیتیکس')

@section('dashboard-content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">آنالیتیکس فروش</h1>
    <a href="{{ route('seller.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت به داشبورد
    </a>
</div>

<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-gray-400 text-xs mb-1">کل بازدید</p>
        <p class="text-2xl font-bold">{{ number_format($totalViews) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-gray-400 text-xs mb-1">کل فروش</p>
        <p class="text-2xl font-bold">{{ number_format($totalSales) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-gray-400 text-xs mb-1">درآمد کل (تومان)</p>
        <p class="text-2xl font-bold">{{ number_format($totalRevenue) }}</p>
    </div>
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
        <p class="text-gray-400 text-xs mb-1">سفارشات این ماه</p>
        <p class="text-2xl font-bold">{{ number_format($thisMonth) }}</p>
    </div>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h2 class="font-bold mb-6">بازدید و فروش هر محصول</h2>
    <canvas id="productChart" height="100"></canvas>
</div>

<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold mb-4">سفارشات</h2>
        <a href="{{ route('seller.orders') }}"
        class="text-sm text-gray-500 hover:text-black transition">
        مشاهده همه سفارشات ←
        </a>
    </div>
    @if($orders->isEmpty())
    <p class="text-gray-400 text-sm text-center py-6">هنوز سفارشی ثبت نشده</p>
    @else
        <div class="space-y-3">
            @foreach($orders as $order)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/' . $order->product->picture_url) }}"
                             class="w-10 h-10 rounded-lg object-cover">

                        <div>
                            <p class="font-medium text-sm">
                                {{ $order->product->title }}
                            </p>

                            <p class="text-gray-400 text-xs">
                                {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($order->created_at)) }}
                            </p>
                        </div>
                    </div>

                    <span class="font-bold text-sm">
                        {{ number_format($order->amount) }} تومان
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const chartData = @json($chartData);

    const labels = chartData.map(p => p.title);
    const views  = chartData.map(p => p.views);
    const sales  = chartData.map(p => p.sales);

    new Chart(document.getElementById('productChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'بازدید',
                    data: views,
                    backgroundColor: 'rgba(0,0,0,0.1)',
                    borderColor: 'rgba(0,0,0,0.8)',
                    borderWidth: 1,
                    borderRadius: 6,
                },
                {
                    label: 'فروش',
                    data: sales,
                    backgroundColor: 'rgba(34,197,94,0.2)',
                    borderColor: 'rgba(34,197,94,0.8)',
                    borderWidth: 1,
                    borderRadius: 6,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>

@endsection
