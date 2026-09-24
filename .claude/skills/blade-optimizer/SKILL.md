---
name: blade-optimizer
description: Optimize Blade templates for performance and maintainability
disable-model-invocation: true
---

# Blade Template Optimizer

Optimize Blade templates for speed and maintainability.

## When to Use
- When pages load slowly
- During code review
- When templates become complex
- Before deployment

## Performance Optimization

### 1. Component Caching

```blade
{{-- Cache expensive components --}}
@php
  $categories = Cache::remember('categories', 3600, fn() => Category::all());
@endphp

@foreach($categories as $category)
  <option value="{{ $category->id }}">{{ $category->name }}</option>
@endforeach
```

### 2. Fragment Caching

```blade
{{-- Cache product card --}}
@cache("product-card-{$product->id}", 600)
  <div class="product-card">
    <h3>{{ $product->title }}</h3>
    <p>{{ $product->price }}</p>
  </div>
@endcache
```

### 3. Avoid N+1 in Views

```blade
{{-- Bad: N+1 query --}}
@foreach($orders as $order)
  <p>{{ $order->product->title }}</p>
@endforeach

{{-- Good: Eager load in controller --}}
@php
  $orders = Order::with('product')->get();
@endphp

@foreach($orders as $order)
  <p>{{ $order->product->title }}</p>
@endforeach
```

### 4. Lazy Loading Images

```blade
<img src="{{ $image }}" loading="lazy" alt="{{ $alt }}">
```

### 5. Section Yields

```blade
{{-- Bad: Inline styles --}}
@section('styles')
  <style>
    .hero { height: 400px; }
  </style>
@endsection

{{-- Good: External CSS --}}
@section('styles')
  @vite(['resources/css/hero.css'])
@endsection
```

## Code Quality

### 1. Extract Components

```blade
{{-- Bad: Repeated code --}}
<div class="flex items-center gap-3 p-4 bg-white rounded-lg shadow-sm">
  <img src="{{ $avatar }}" class="w-12 h-12 rounded-full">
  <div>
    <p class="font-medium">{{ $name }}</p>
    <p class="text-sm text-gray-500">{{ $email }}</p>
  </div>
</div>

{{-- Good: Reusable component --}}
<x-user-card :user="$user" />
```

### 2. Use Slots Effectively

```blade
{{-- card.blade.php --}}
<div class="bg-white rounded-lg shadow-sm p-6">
  @if(isset($header))
    <div class="mb-4">{{ $header }}</div>
  @endif
  
  {{ $slot }}
  
  @if(isset($footer))
    <div class="mt-4 pt-4 border-t">{{ $footer }}</div>
  @endif
</div>

{{-- Usage --}}
<x-card>
  <x-slot name="header">
    <h3>Card Title</h3>
  </x-slot>
  
  <p>Card content</p>
  
  <x-slot name="footer">
    <button>Action</button>
  </x-slot>
</x-card>
```

### 3. Conditional Rendering

```blade
{{-- Bad: Multiple checks --}}
@if($user)
  @if($user->isAdmin())
    <span>Admin</span>
  @endif
@endif

{{-- Good: Null safe --}}
<span>{{ $user?->isAdmin() ? 'Admin' : '' }}</span>
```

### 4. Blade Directives

```blade
{{-- Foreach with key --}}
@foreach($items as $key => $item)
  <div data-index="{{ $key }}">{{ $item }}</div>
@endforeach

{{-- Once for static content --}}
@once
  <div>{{ $staticContent }}</div>
@endonce

{{-- Production check --}}
@if(app()->environment('production'))
  <script src="{{ asset('js/analytics.js') }}"></script>
@endif
```

## Maintainability

### 1. Comments

```blade
{{-- 
  Product Card Component
  
  @param \App\Models\Product $product
  @param bool $showSaveButton
--}}
```

### 2. Meaningful Variables

```blade
{{-- Bad --}}
@foreach($d as $i)
  {{ $i['n'] }}
@endforeach

{{-- Good --}}
@foreach($products as $product)
  {{ $product->title }}
@endforeach
```

### 3. Consistent Formatting

```blade
{{-- Bad: Inconsistent --}}
<div class="p-4 mb-2">
<div class="p-6 mb-4">

{{-- Good: Consistent --}}
<div class="p-6 mb-4">
<div class="p-6 mb-4">
```

## Common Patterns

### Empty State

```blade
@if($items->isEmpty())
  <div class="text-center py-12">
    <x-icon name="inbox" class="w-12 h-12 text-gray-400 mx-auto mb-4" />
    <h3 class="text-lg font-medium text-gray-900 mb-2">No items yet</h3>
    <p class="text-gray-500">Get started by creating your first item.</p>
  </div>
@endif
```

### Loading State

```blade
<div x-data="{ loading: true }" x-init="loading = false">
  @if($loading)
    <div class="animate-pulse space-y-4">
      <div class="h-4 bg-gray-200 rounded w-3/4"></div>
      <div class="h-4 bg-gray-200 rounded w-1/2"></div>
    </div>
  @else
    {{ $slot }}
  @endif
</div>
```

### Error State

```blade
@if($errors->any())
  <div class="bg-red-50 border border-red-200 rounded-lg p-4">
    <ul class="list-disc list-inside text-red-600">
      @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
```

## Performance Checklist

- [ ] Components cached where appropriate
- [ ] No N+1 queries in views
- [ ] Images lazy loaded
- [ ] External CSS/JS used
- [ ] Section yields for assets

## Code Quality Checklist

- [ ] Components extracted for reuse
- [ ] Slots used effectively
- [ ] Variables named meaningfully
- [ ] Comments on complex logic
- [ ] Consistent formatting

## Files to Optimize

Priority order:
1. [ ] resources/views/layouts/app.blade.php
2. [ ] resources/views/home.blade.php
3. [ ] resources/views/products/show.blade.php
4. [ ] resources/views/wallet/index.blade.php
5. [ ] resources/views/orders/show.blade.php

Target: Fast, maintainable, clean templates
