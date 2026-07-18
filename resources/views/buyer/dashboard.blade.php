@extends('layouts.app')

@section('title', 'داشبورد خریدار')

@section('content')

<h1 class="text-2xl font-bold mb-8">داشبورد من</h1>

{{-- خریدها --}}
<div class="mb-10" x-data="section('/buyer/api/orders')">
    <h2 class="text-lg font-bold mb-4">خریدهای من
        <span class="text-gray-400 text-sm font-normal" x-show="total > 0" x-text="'(' + total + ')'"></span>
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <template x-for="order in items" :key="order.id">
            <a :href="order.order_url"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition group">
                <img :src="order.picture_url"
                    class="w-full h-40 object-cover group-hover:scale-105 transition duration-300">
                <div class="p-4">
                    <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full"
                        x-text="order.category"></span>
                    <h3 class="font-bold text-sm mt-2 mb-1" x-text="order.title"></h3>
                    <div class="flex items-center justify-between mt-2">
                        <p class="font-bold text-sm" x-text="order.amount + ' تومان'"></p>
                        <template x-if="order.file_url">
                            <a :href="order.file_url" download
                                @click.prevent.stop="window.location.href = order.file_url"
                                class="text-xs bg-black text-white px-3 py-1 rounded-lg hover:bg-gray-800 transition">
                                دانلود
                            </a>
                        </template>
                    </div>
                    <span class="text-xs text-green-600 bg-green-50 px-2 py-0.5 rounded-full mt-2 inline-block">
                        پرداخت شده
                    </span>
                </div>
            </a>
        </template>
    </div>

    {{-- خالی --}}
    <div x-show="items.length === 0 && !isLoading"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-gray-400">
        <p>هنوز خریدی انجام نداده‌اید</p>
        <a href="{{ route('explore') }}" class="text-black underline text-sm mt-2 inline-block">
            کاوش محصولات
        </a>
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

{{-- ذخیره‌ها --}}
<div x-data="section('/buyer/api/saves')">
    <h2 class="text-lg font-bold mb-4">ذخیره‌های من
        <span class="text-gray-400 text-sm font-normal" x-show="total > 0" x-text="'(' + total + ')'"></span>
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
        <template x-for="save in items" :key="save.id">
            <a :href="save.url"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition group">
                <img :src="save.picture_url" class="w-full h-40 object-cover group-hover:scale-105 transition duration-300">
                <div class="p-4">
                    <h3 class="font-bold text-sm mb-1" x-text="save.title"></h3>
                    <p class="text-gray-400 text-xs mb-2" x-text="save.seller"></p>
                    <p class="font-bold text-sm" x-text="save.price + ' تومان'"></p>
                </div>
            </a>
        </template>
    </div>

    {{-- خالی --}}
    <div x-show="items.length === 0 && !isLoading"
        class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-gray-400">
        <p>هنوز محصولی ذخیره نکرده‌اید</p>
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
    function section(apiUrl) {
        return {
            items: [],
            isLoading: false,
            currentPage: 1,
            lastPage: 1,
            total: 0,

            init() {
                this.fetch();
            },

            async fetch() {
                this.isLoading = true;
                try {
                    const res = await fetch(`${apiUrl}?page=${this.currentPage}`, {
                        headers: {
                            'Accept': 'application/json'
                        }
                    });
                    const json = await res.json();
                    this.items = this.currentPage === 1 ?
                        json.data :
                        [...this.items, ...json.data];
                    this.lastPage = json.last_page;
                    this.total = json.data.length + (this.currentPage - 1) * 4;
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
            }
        }
    }
</script>

@endsection