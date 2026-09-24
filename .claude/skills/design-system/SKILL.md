---
name: design-system
description: Create and maintain a comprehensive design system with reusable components
disable-model-invocation: true
---

# Design System Manager

Build and maintain a consistent design system.

## When to Use
- Starting new feature development
- When inconsistencies appear
- During design reviews
- For team alignment

## Design System Structure

### 1. Design Tokens

**Colors (resources/css/variables.css)**
```css
:root {
  /* Primary */
  --color-primary-50: #eff6ff;
  --color-primary-500: #3b82f6;
  --color-primary-600: #2563eb;
  
  /* Neutral */
  --color-gray-50: #f9fafb;
  --color-gray-900: #111827;
  
  /* Semantic */
  --color-success: #22c55e;
  --color-warning: #f59e0b;
  --color-error: #ef4444;
}
```

**Typography**
```css
:root {
  --font-family: 'Inter', sans-serif;
  --font-size-xs: 0.75rem;
  --font-size-sm: 0.875rem;
  --font-size-base: 1rem;
  --font-size-lg: 1.125rem;
  --font-size-xl: 1.25rem;
  --font-size-2xl: 1.5rem;
}
```

**Spacing**
```css
:root {
  --space-1: 0.25rem;
  --space-2: 0.5rem;
  --space-4: 1rem;
  --space-6: 1.5rem;
  --space-8: 2rem;
}
```

### 2. Component Library

**Button Component**
```blade
{{-- resources/views/components/ui/button.blade.php --}}
@props([
  'variant' => 'primary',
  'size' => 'md',
  'disabled' => false,
])

@php
$baseClasses = 'inline-flex items-center justify-center font-semibold rounded-lg 
                 transition-colors duration-200 focus:outline-none focus:ring-2 
                 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';

$variants = [
  'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
  'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50',
  'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
  'ghost' => 'text-gray-700 hover:bg-gray-100',
];

$sizes = [
  'sm' => 'px-3 py-1.5 text-sm',
  'md' => 'px-4 py-2 text-base',
  'lg' => 'px-6 py-3 text-lg',
];
@endphp

<button {{ $attributes->merge([
  'class' => "$baseClasses {$variants[$variant]} {$sizes[$size]}",
  'disabled' => $disabled,
]) }}>
  {{ $slot }}
</button>
```

**Card Component**
```blade
{{-- resources/views/components/ui/card.blade.php --}}
@props(['padding' => true])

<div {{ $attributes->merge([
  'class' => 'bg-white rounded-xl shadow-sm border border-gray-100 ' . 
             ($padding ? 'p-6' : '')
]) }}>
  {{ $slot }}
</div>
```

**Input Component**
```blade
{{-- resources/views/components/ui/input.blade.php --}}
@props([
  'label' => null,
  'error' => null,
  'required' => false,
])

<div class="space-y-1">
  @if($label)
    <label class="block text-sm font-medium text-gray-700">
      {{ $label }}
      @if($required)
        <span class="text-red-500">*</span>
      @endif
    </label>
  @endif
  
  <input {{ $attributes->merge([
    'class' => 'w-full px-4 py-3 border border-gray-300 rounded-lg 
                focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
                transition-colors duration-200'
  ]) }}>
  
  @if($error)
    <p class="text-sm text-red-600">{{ $error }}</p>
  @endif
</div>
```

### 3. Page Templates

**Product Listing Page**
```blade
@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
  {{-- Header --}}
  <div class="flex items-center justify-between mb-8">
    <h1 class="text-2xl font-bold text-gray-900">Explore Products</h1>
    
    {{-- Search --}}
    <x-ui.input 
      type="search" 
      placeholder="Search products..." 
      class="w-64"
    />
  </div>
  
  {{-- Filters --}}
  <div class="flex gap-4 mb-6">
    @foreach($categories as $category)
      <x-ui.button variant="ghost">{{ $category->name }}</x-ui.button>
    @endforeach
  </div>
  
  {{-- Product Grid --}}
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
    @foreach($products as $product)
      @include('components.product-card', compact('product'))
    @endforeach
  </div>
  
  {{-- Pagination --}}
  <div class="mt-8">
    {{ $products->links() }}
  </div>
</div>
@endsection
```

## Usage Guidelines

### When to Use Each Variant

**Buttons:**
- Primary: Main actions (Buy, Submit, Save)
- Secondary: Secondary actions (Cancel, Close)
- Danger: Destructive actions (Delete, Remove)
- Ghost: Tertiary actions (More options, Expand)

**Cards:**
- Product cards: Product listing
- Info cards: Dashboard widgets
- Form cards: Forms in modals

**Inputs:**
- Text: Names, titles
- Email: Email addresses
- Password: Passwords
- Number: Prices, quantities

## File Structure

```
resources/
  css/
    variables.css          # Design tokens
    components.css         # Component styles
  views/
    components/
      ui/
        button.blade.php   # Button component
        card.blade.php     # Card component
        input.blade.php    # Input component
        badge.blade.php    # Badge component
        modal.blade.php    # Modal component
      layouts/
        app.blade.php      # Main layout
        guest.blade.php    # Guest layout
```

## Maintenance

### Adding New Components
1. Create component in resources/views/components/ui/
2. Add to this documentation
3. Update storybook (if using)
4. Test in all variants

### Updating Components
1. Check all usages first
2. Update component file
3. Update documentation
4. Test thoroughly

## Files to Create

1. [ ] resources/css/variables.css
2. [ ] resources/css/components.css
3. [ ] resources/views/components/ui/button.blade.php
4. [ ] resources/views/components/ui/card.blade.php
5. [ ] resources/views/components/ui/input.blade.php
6. [ ] resources/views/components/ui/badge.blade.php
7. [ ] resources/views/components/ui/modal.blade.php
8. [ ] docs/design-system.md

Target: Consistent, maintainable, scalable design
