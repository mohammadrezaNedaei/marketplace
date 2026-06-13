@extends('layouts.app')

@section('title', 'کاوش')

@section('content')

<div x-data="explore()">

    <h1 class="text-2xl font-bold mb-6">کاوش محصولات</h1>

    {{-- فیلترها --}}
    <div class="flex flex-wrap gap-3 mb-6">

        <input type="text" x-model="search"
            placeholder="جستجو..."
            class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black flex-1 min-w-48">

        <select x-model="selectedCategory"
            class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
            <option value="">همه دسته‌ها</option>
            @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <select x-model="sort"
            class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-black">
            <option value="newest">جدیدترین</option>
            <option value="popular">پربازدیدترین</option>
            <option value="price_asc">ارزان‌ترین</option>
            <option value="price_desc">گران‌ترین</option>
        </select>

        <button @click="reset()"
            class="border border-gray-300 px-5 py-2 rounded-lg text-sm hover:bg-gray-50 transition">
            پاک کردن
        </button>

    </div>

    {{-- تعداد نتایج --}}
    <p class="text-sm text-gray-400 mb-4" x-text="total + ' محصول یافت شد'"></p>

    {{-- گرید محصولات --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <template x-for="product in products" :key="product.id">
            <a :href="product.url"
                class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition group">

                <div class="relative">
                    <img :src="product.picture_url" :alt="product.title"
                        class="w-full h-48 object-cover group-hover:scale-105 transition duration-300">
                    <span class="absolute top-2 right-2 bg-white text-xs px-2 py-1 rounded-full shadow-sm"
                        x-text="product.category"></span>
                </div>

                <div class="p-4">
                    <h2 class="font-bold text-sm mb-1 line-clamp-2" x-text="product.title"></h2>
                    <p class="text-gray-400 text-xs mb-3" x-text="product.seller"></p>

                    <div class="flex items-center justify-between">
                        <div>
                            <span class="font-bold text-sm"
                                x-text="formatPrice(product.discount_price || product.price)"></span>
                            <template x-if="product.discount_price">
                                <span class="text-xs text-gray-400 line-through mr-1"
                                    x-text="formatPrice(product.price)"></span>
                            </template>
                            <span class="text-xs text-gray-500">تومان</span>
                        </div>
                        <span class="text-xs text-gray-400" x-text="product.views + ' بازدید'"></span>
                    </div>
                </div>

            </a>
        </template>
    </div>

    {{-- هیچ نتیجه‌ای نبود --}}
    <div x-show="products.length === 0 && !isLoading" class="text-center text-gray-400 mt-20">
        <p class="text-lg">محصولی یافت نشد</p>
    </div>

    {{-- لودینگ --}}
    <div x-show="isLoading" class="flex justify-center mt-10">
        <div class="w-6 h-6 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
    </div>

    {{-- دکمه بارگذاری بیشتر --}}
    <div x-show="hasMore && !isLoading" class="flex justify-center mt-10">
        <button @click="loadMore()"
            class="border border-gray-300 px-8 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بارگذاری بیشتر
        </button>
    </div>

</div>

<script>
    function explore() {
        return {
            search: '',
            selectedCategory: '',
            sort: 'newest',
            products: [],
            isLoading: false,
            currentPage: 1,
            lastPage: 1,
            total: 0,

            init() {
                this.fetchProducts();
                this.$watch('search', Alpine.debounce(() => this.resetAndFetch(), 300));
                this.$watch('selectedCategory', () => this.resetAndFetch());
                this.$watch('sort', () => this.resetAndFetch());
            },

            resetAndFetch() {
                this.currentPage = 1;
                this.products = [];
                this.fetchProducts();
            },

            async fetchProducts() {
                this.isLoading = true;

                const params = new URLSearchParams({
                    search: this.search,
                    category_id: this.selectedCategory,
                    sort: this.sort,
                    page: this.currentPage,
                });

                try {
                    const response = await fetch(`/api/products?${params}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    });

                    if (response.ok) {
                        const json = await response.json();
                        this.products = this.currentPage === 1 ?
                            json.data : [...this.products, ...json.data];
                        this.lastPage = json.last_page;
                        this.total = json.total;
                    }
                } catch (error) {
                    console.error('خطا در دریافت محصولات:', error);
                } finally {
                    this.isLoading = false;
                }
            },

            loadMore() {
                if (this.currentPage < this.lastPage) {
                    this.currentPage++;
                    this.fetchProducts();
                }
            },

            get hasMore() {
                return this.currentPage < this.lastPage;
            },

            reset() {
                this.search = '';
                this.selectedCategory = '';
                this.sort = 'newest';
            },

            formatPrice(price) {
                return Number(price).toLocaleString('fa-IR');
            }
        }
    }
</script>

@endsection