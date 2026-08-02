@extends('layouts.dashboard')

@section('title', 'دسته بندی ها')

@section('dashboard-content')

    <div class="flex items-center justify-between mb-8">

        <h1 class="text-2xl font-bold">
            دسته بندی ها
        </h1>

        <div>
        <a href="{{ route('admin.categories.create') }}" class="bg-black text-white px-5 py-2 rounded-full text-sm ml-2">
            + افزودن دسته بندی
        </a>
        <a href="{{ route('admin.dashboard') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت
        </a>
        </div>
    </div>


    @if (session('success'))
        <div class="bg-green-50 text-green-700 p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif


    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">


        <table class="w-full text-sm">

            <thead>
                <tr class="ml-6 border-b border-gray-200 text-gray-400">

                    <th class="text-right py-3 w-5/6">
                        نام
                    </th>

                    <th class="text-center py-3">
                        عملیات
                    </th>

                </tr>
            </thead>


            <tbody>

                @foreach ($categories as $category)
                    <tr class="border-b border-gray-100">

                        <td class="py-4">
                            {{ $category->name }}
                        </td>


                        <td class="py-4 text-left">
                            <div class="flex justify-end gap-3">

                                <a href="{{ route('admin.categories.edit', $category) }}"
                                    class="border border-gray-300 px-4 py-1 rounded-lg hover:bg-gray-50 transition">
                                    ویرایش
                                </a>

                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="border border-red-300 text-red-600 px-4 py-1 rounded-lg hover:bg-red-50 transition">
                                        حذف
                                    </button>
                                </form>

                            </div>
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>


        <div class="mt-6">
            {{ $categories->links() }}
        </div>


    </div>

@endsection
