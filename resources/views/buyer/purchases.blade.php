@extends('layouts.app')

@section('title', 'خریدهای من')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">همه خریدهای من</h1>
    <a href="{{ route('buyer.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت به داشبورد
    </a>
</div>

<div x-data="section('/buyer/api/orders')">

    <input type="text" x-model="search"
        placeholder="جستجو در خریدها..."
        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black mb-6">
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

    <div x-show="items.length === 0 && !isLoading"
         class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <p>هنوز خریدی انجام نداده‌اید</p>
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
    function section(apiUrl) {
        return {
            items: [],
            isLoading: false,
            currentPage: 1,
            lastPage: 1,
            search: '',

            init() {
                this.fetch();
                this.$watch('search', Alpine.debounce(() => this.resetAndFetch(), 300));
            },

            resetAndFetch() {
                this.currentPage = 1;
                this.items = [];
                this.fetch();
            },

            async fetch() {
                this.isLoading = true;
                try {
                    const params = new URLSearchParams({
                        search: this.search,
                        page:   this.currentPage,
                    });
                    const res  = await fetch(`${apiUrl}?${params}`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const json = await res.json();
                    this.items    = this.currentPage === 1 ? json.data : [...this.items, ...json.data];
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
            }
        }
    }
</script>

@endsection
