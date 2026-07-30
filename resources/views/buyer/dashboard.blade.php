@extends('layouts.app')

@section('title', 'داشبورد خریدار')

@section('content')

    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold">داشبورد من</h1>
        <div>

            <a href="{{ route('buyer.payments') }}"
                class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
                تاریخچه پرداخت‌ها
            </a>
            <a href="{{ route('profile.edit') }}"
                class="border border-gray-300 px-5 py-2 mr-2 rounded-full text-sm hover:bg-gray-50 transition">
                ویرایش پروفایل
            </a>
        </div>
    </div>

    <div class="mb-10" x-data="preview('/buyer/api/orders')">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold">خریدهای من</h2>
            <a href="{{ route('buyer.purchases') }}" class="text-sm text-gray-500 hover:text-black transition">
                مشاهده همه ←
            </a>
        </div>

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
                        <p class="font-bold text-sm" x-text="order.amount + ' تومان'"></p>
                    </div>
                </a>
            </template>
        </div>

        <div x-show="items.length === 0 && !isLoading"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-gray-400">
            <p>هنوز خریدی انجام نداده‌اید</p>
            <a href="{{ route('explore') }}" class="text-black underline text-sm mt-2 inline-block">
                کاوش محصولات
            </a>
        </div>

        <div x-show="isLoading" class="flex justify-center mt-6">
            <div class="w-5 h-5 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    <div x-data="preview('/buyer/api/saves')">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-bold">ذخیره‌های من</h2>
            <a href="{{ route('buyer.saves') }}" class="text-sm text-gray-500 hover:text-black transition">
                مشاهده همه ←
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <template x-for="save in items" :key="save.id">
                <a :href="save.url"
                    class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition group">
                    <img :src="save.picture_url"
                        class="w-full h-40 object-cover group-hover:scale-105 transition duration-300">
                    <div class="p-4">
                        <h3 class="font-bold text-sm mb-1" x-text="save.title"></h3>
                        <p class="text-gray-400 text-xs mb-2" x-text="save.seller"></p>
                        <p class="font-bold text-sm" x-text="save.price + ' تومان'"></p>
                    </div>
                </a>
            </template>
        </div>

        <div x-show="items.length === 0 && !isLoading"
            class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8 text-center text-gray-400">
            <p>هنوز محصولی ذخیره نکرده‌اید</p>
        </div>

        <div x-show="isLoading" class="flex justify-center mt-6">
            <div class="w-5 h-5 border-2 border-black border-t-transparent rounded-full animate-spin"></div>
        </div>
    </div>

    <script>
        function preview(apiUrl) {
            return {
                items: [],
                isLoading: false,

                init() {
                    this.fetch();
                },

                async fetch() {
                    this.isLoading = true;
                    try {
                        const res = await fetch(`${apiUrl}?page=1`, {
                            headers: {
                                'Accept': 'application/json'
                            }
                        });
                        const json = await res.json();
                        this.items = json.data.slice(0, 3);
                    } catch (e) {
                        console.error(e);
                    } finally {
                        this.isLoading = false;
                    }
                }
            }
        }
    </script>

@endsection
