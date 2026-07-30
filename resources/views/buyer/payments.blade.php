@extends('layouts.app')

@section('title', 'تاریخچه پرداخت‌ها')

@section('content')

<div class="max-w-3xl mx-auto" x-data="payments()">

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">تاریخچه پرداخت‌ها</h1>
        <a href="{{ route('buyer.dashboard') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت به داشبورد
        </a>
    </div>

    {{-- فیلتر وضعیت --}}
    <div class="flex gap-2 mb-6">
        <template x-for="option in statusOptions" :key="option.value">
            <button @click="setStatus(option.value)"
                class="px-4 py-1.5 rounded-full text-sm border transition"
                :class="status === option.value
                    ? 'bg-black text-white border-black'
                    : 'border-gray-300 hover:bg-gray-50'"
                x-text="option.label">
            </button>
        </template>
    </div>

    {{-- لیست پرداخت‌ها --}}
    <div class="space-y-3">
        <template x-for="order in orders" :key="order.id">
            <a :href="order.order_url"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4 hover:shadow-md transition">

                <img :src="order.picture_url" class="w-14 h-14 rounded-xl object-cover">

                <div class="flex-1">
                    <p class="font-medium text-sm" x-text="order.title"></p>
                    <p class="text-gray-400 text-xs mt-0.5" x-text="order.category"></p>
                </div>

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
            </a>
        </template>
    </div>

    {{-- خالی --}}
    <div x-show="orders.length === 0 && !isLoading"
         class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <p>پرداختی یافت نشد</p>
    </div>

    {{-- لودینگ --}}
    <div x-show="isLoading" class="flex justify-center mt-6">
        <div class="w-5 h-5 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
    </div>

    {{-- بارگذاری بیشتر --}}
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
            statusOptions: [
                { value: '',          label: 'همه' },
                { value: 'pending',   label: 'در انتظار' },
                { value: 'paid',      label: 'پرداخت شده' },
                { value: 'delivered', label: 'تحویل شده' },
                { value: 'cancelled', label: 'لغو شده' },
            ],

            init() {
                this.fetch();
            },

            setStatus(value) {
                this.status = value;
                this.currentPage = 1;
                this.orders = [];
                this.fetch();
            },

            async fetch() {
                this.isLoading = true;
                try {
                    const params = new URLSearchParams({
                        status: this.status,
                        page:   this.currentPage,
                    });
                    const res  = await fetch(`{{ route('buyer.api.payments') }}?${params}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const json = await res.json();
                    this.orders   = this.currentPage === 1 ? json.data : [...this.orders, ...json.data];
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
            }
        }
    }
</script>

@endsection
