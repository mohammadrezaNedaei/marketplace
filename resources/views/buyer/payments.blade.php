@extends('layouts.dashboard')

@section('title', 'تاریخچه پرداخت‌ها')

@section('dashboard-content')

    <div class="max-w-3xl mx-auto" x-data="payments()">

        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-bold">تاریخچه پرداخت‌ها</h1>
            <a href="{{ route('buyer.dashboard') }}"
                class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
                بازگشت به داشبورد
            </a>
        </div>

        <div class="flex gap-2 mb-4">
            <template x-for="option in statusOptions" :key="option.value">
                <button @click="setStatus(option.value)" class="px-4 py-1.5 rounded-full text-sm border transition"
                    :class="status === option.value ?
                        'bg-black text-white border-black' :
                        'border-gray-300 hover:bg-gray-50'"
                    x-text="option.label">
                </button>
            </template>
        </div>

        <div class="flex flex-wrap gap-3 mb-6 items-center">
            <input type="text" id="from_date_input" data-jdp dir="ltr" readonly autocomplete="off"
                placeholder="از تاریخ"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

            <input type="text" id="to_date_input" data-jdp dir="ltr" readonly autocomplete="off"
                placeholder="تا تاریخ"
                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black text-center cursor-pointer">

            <button @click="applyDateFilter()"
                class="bg-black text-white px-5 py-2 rounded-lg text-sm hover:bg-gray-800 transition">
                اعمال فیلتر تاریخ
            </button>

            <button x-show="fromDate || toDate" @click="clearDateFilter()"
                class="border border-gray-300 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
                پاک کردن
            </button>
        </div>

        <div class="space-y-3">
            <template x-for="order in orders" :key="order.id">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">

                    <img :src="order.picture_url" class="w-14 h-14 rounded-xl object-cover">

                    <a :href="order.order_url" class="flex-1">
                        <p class="font-medium text-sm" x-text="order.title"></p>
                        <p class="text-gray-400 text-xs mt-0.5" x-text="order.category"></p>
                        <p class="text-gray-400 text-xs mt-0.5" x-text="order.date"></p>
                    </a>

                    <div class="text-left">
                        <p class="font-bold text-sm" x-text="order.amount + ' تومان'"></p>
                        <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block"
                            :class="{
                                'bg-yellow-100 text-yellow-700': order.status === 'pending',
                                'bg-green-100 text-green-700': order.status === 'paid',
                                'bg-blue-100 text-blue-700': order.status === 'delivered',
                                'bg-red-100 text-red-700': order.status === 'cancelled',
                            }"
                            x-text="statusLabel(order.status)">
                        </span>
                    </div>

                    <template x-if="order.pay_url">
                        <form :action="order.pay_url" method="POST" @submit.prevent="pay(order.pay_url)">
                            <button type="submit"
                                class="text-xs bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                                پرداخت
                            </button>
                        </form>
                    </template>

                </div>
            </template>
        </div>

        <div x-show="orders.length === 0 && !isLoading"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
            <p>پرداختی یافت نشد</p>
        </div>

        <div x-show="isLoading" class="flex justify-center mt-6">
            <div class="w-5 h-5 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
        </div>

        <div x-show="hasMore && !isLoading" class="flex justify-center mt-6">
            <button @click="loadMore()"
                class="border border-gray-300 px-8 py-2 rounded-full text-sm hover:bg-gray-50 transition">
                بارگذاری بیشتر
            </button>
        </div>

    </div>

    <script>
        function payments() {
            return {
                orders: [],
                isLoading: false,
                currentPage: 1,
                lastPage: 1,
                status: '',
                fromDate: '',
                toDate: '',
                statusOptions: [{
                        value: '',
                        label: 'همه'
                    },
                    {
                        value: 'pending',
                        label: 'در انتظار'
                    },
                    {
                        value: 'paid',
                        label: 'پرداخت شده'
                    },
                    {
                        value: 'delivered',
                        label: 'تحویل شده'
                    },
                    {
                        value: 'cancelled',
                        label: 'لغو شده'
                    },
                ],

                init() {
                    this.fetch();
                    jalaliDatepicker.startWatch({
                        persianDigits: true,
                        autoHide: true,
                        autoReadOnlyInput: true
                    });
                },

                setStatus(value) {
                    this.status = value;
                    this.currentPage = 1;
                    this.orders = [];
                    this.fetch();
                },

                applyDateFilter() {
                    this.fromDate = document.getElementById('from_date_input').value;
                    this.toDate = document.getElementById('to_date_input').value;
                    this.currentPage = 1;
                    this.orders = [];
                    this.fetch();
                },

                clearDateFilter() {
                    document.getElementById('from_date_input').value = '';
                    document.getElementById('to_date_input').value = '';
                    this.fromDate = '';
                    this.toDate = '';
                    this.currentPage = 1;
                    this.orders = [];
                    this.fetch();
                },

                async fetch() {
                    this.isLoading = true;
                    try {
                        const params = new URLSearchParams({
                            status: this.status,
                            from_date: this.fromDate,
                            to_date: this.toDate,
                            page: this.currentPage,
                        });
                        const res = await fetch(`{{ route('buyer.api.payments') }}?${params}`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        const json = await res.json();
                        this.orders = this.currentPage === 1 ? json.data : [...this.orders, ...json.data];
                        this.lastPage = json.last_page;
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isLoading = false;
                    }
                },

                loadMore() {
                    if (this.currentPage < this.lastPage) {
                        this.currentPage++;
                        this.fetch();
                    }
                },

                get hasMore() {
                    return this.currentPage < this.lastPage;
                },

                statusLabel(status) {
                    return this.statusOptions.find(o => o.value === status)?.label || status;
                },

                async pay(url) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = url;
                    form.innerHTML = `@csrf`;
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }
    </script>

@endsection
