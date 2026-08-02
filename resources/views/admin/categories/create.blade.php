@extends('layouts.dashboard')

@section('title', 'ایجاد دسته بندی')

@section('dashboard-content')
    <div class="flex items-center justify-between mb-8">

        <h1 class="text-2xl font-bold">
            ایجاد دسته بندی
        </h1>

        <div>
        <a href="{{ route('admin.categories') }}"
            class="border border-gray-300 px-5 py-2 rounded-full text-sm hover:bg-gray-50 transition">
            بازگشت
        </a>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white p-6 rounded-2xl border border-gray-300">

        @csrf
        <input name="name" placeholder="نام دسته بندی" class="w-full border border-gray-300 rounded-lg px-4 py-2 mb-4">

        <button class="bg-black text-white px-6 py-2 rounded-lg">
            ذخیره
        </button>

    </form>

@endsection
