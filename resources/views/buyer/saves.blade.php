@extends('layouts.app')

@section('title', 'ذخیره‌های من')

@section('content')

<div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold">همه ذخیره‌های من</h1>
    <a href="{{ route('buyer.dashboard') }}"
        class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
        بازگشت به داشبورد
    </a>
</div>

<div x-data="section('/buyer/api/saves')">
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

    <div x-show="items.length === 0 && !isLoading"
         class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center text-gray-400">
        <p>هنوز محصولی ذخیره نکرده‌اید</p>
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

            init() {
                this.fetch();
            },

            async fetch() {
                this.isLoading = true;
                try {
                    const res  = await fetch(`${apiUrl}?page=${this.currentPage}`, {
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
