@extends('layouts.dashboard')

@section('title', 'سفارشات من')

@section('dashboard-content')

    <div class="flex items-center justify-between mb-8">

        <h1 class="text-2xl font-bold">
            سفارشات من
        </h1>

        <a href="{{ route('seller.analytics') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50">
            بازگشت
        </a>

    </div>


    <form method="GET" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 mb-6">

        <div class="flex flex-wrap gap-3">

            <input name="search" value="{{ request('search') }}" placeholder="جستجوی محصول..."
                class="border rounded-lg px-4 py-2 text-sm">


            <input name="from_date" data-jdp readonly value="{{ request('from_date') }}" placeholder="از تاریخ"
                class="border rounded-lg px-4 py-2 text-sm">


            <input name="to_date" data-jdp readonly value="{{ request('to_date') }}" placeholder="تا تاریخ"
                class="border rounded-lg px-4 py-2 text-sm">


            <button class="bg-black text-white px-6 py-2 rounded-lg text-sm">
                جستجو
            </button>

        </div>

    </form>


    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">


        @if ($orders->isEmpty())

            <p class="text-gray-400 text-center py-8">
                سفارشی پیدا نشد
            </p>
        @else
            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead>
                        <tr class="text-gray-400 border-b">

                            <th class="text-right py-3">
                                محصول
                            </th>

                            <th class="text-right py-3">
                                مبلغ
                            </th>

                            <th class="text-right py-3">
                                وضعیت
                            </th>

                            <th class="text-right py-3">
                                تاریخ
                            </th>

                        </tr>
                    </thead>


                    <tbody>

                        @foreach ($orders as $order)
                            <tr class="border-b border-gray-50">

                                <td class="py-4">

                                    <div class="flex items-center gap-3">

                                        <img src="{{ asset('storage/' . $order->product->picture_url) }}"
                                            class="w-10 h-10 rounded-lg object-cover">

                                        <span>
                                            {{ $order->product->title }}
                                        </span>

                                    </div>

                                </td>


                                <td>
                                    {{ number_format($order->amount) }} تومان
                                </td>


                                <td>

                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded-full text-xs">
                                        {{ $order->status }}
                                    </span>

                                </td>


                                <td class="text-gray-400">

                                    {{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($order->created_at)) }}

                                </td>


                            </tr>
                        @endforeach

                    </tbody>

                </table>

            </div>


            <div class="mt-6">
                {{ $orders->links() }}
            </div>


        @endif


    </div>


    <script>
        jalaliDatepicker.startWatch({
            persianDigits: true,
            autoHide: true,
            autoReadOnlyInput: true
        });
    </script>


@endsection
