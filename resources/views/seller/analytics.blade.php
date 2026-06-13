@extends('layouts.app')

@section('title', 'آنالیتیکس')

@section('content')

{{-- هدر --}}
<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">آنالیتیکس فروش</h1>
    <a href="{{ route('seller.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت به داشبورد
    </a>
</div>

{{-- کارت‌های آمار کلی --}}
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
        <p class="text-2xl font-bold">{{ $thisMonth->count() }}</p>
    </div>
</div>

{{-- چارت بازدید و فروش --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h2 class="font-bold mb-6">بازدید و فروش هر محصول</h2>
    <canvas id="productChart" height="100"></canvas>
</div>

{{-- پرفروش‌ترین این ماه --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-8">
    <h2 class="font-bold mb-4">سفارشات این ماه</h2>

    @if($thisMonth->isEmpty())
        <p class="text-gray-400 text-sm text-center py-6">هنوز سفارشی در این ماه ثبت نشده</p>
    @else
        <div class="space-y-3">
            @foreach($thisMonth as $order)
                <div class="flex items-center justify-between py-3 border-b border-gray-100 last:border-0">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('storage/' . $order->product->picture_url) }}"
                             class="w-10 h-10 rounded-lg object-cover">
                        <div>
                            <p class="font-medium text-sm">{{ $order->product->title }}</p>
                            <p class="text-gray-400 text-xs">{{ $order->created_at }}</p>
                        </div>
                    </div>
                    <span class="font-bold text-sm">{{ number_format($order->amount) }} تومان</span>
                </div>
            @endforeach
        </div>
    @endif
</div>

{{-- جدول همه سفارشات --}}
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
    <h2 class="font-bold mb-4">همه سفارشات</h2>

    @if($orders->isEmpty())
        <p class="text-gray-400 text-sm text-center py-6">هنوز سفارشی ثبت نشده</p>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-gray-400 border-b border-gray-100">
                        <th class="text-right pb-3 font-medium">محصول</th>
                        <th class="text-right pb-3 font-medium">مبلغ</th>
                        <th class="text-right pb-3 font-medium">وضعیت</th>
                        <th class="text-right pb-3 font-medium">تاریخ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        <tr class="border-b border-gray-50 last:border-0">
                            <td class="py-3">{{ $order->product->title }}</td>
                            <td class="py-3">{{ number_format($order->amount) }} تومان</td>
                            <td class="py-3">
                                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="py-3 text-gray-400">{{ $order->created_at }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

{{-- Chart.js --}}
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