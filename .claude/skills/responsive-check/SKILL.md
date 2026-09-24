---
name: responsive-check
description: Validate mobile-first responsive design across all breakpoints
disable-model-invocation: true
---

# Responsive Design Checker

Ensure perfect responsive design across all devices.

## When to Use
- After creating/modifying layouts
- Before deployment
- During cross-device testing
- When fixing mobile issues

## Breakpoints (Tailwind CSS)

```css
sm: 640px    /* Small devices (landscape phones) */
md: 768px    /* Medium devices (portrait tablets) */
lg: 1024px   /* Large devices (landscape tablets) */
xl: 1280px   /* Extra large devices (large laptops) */
2xl: 1536px  /* Extra extra large (desktops) */
```

## Mobile-First Checklist

### 1. Touch Targets

**Minimum sizes:**
- Buttons: 44px x 44px
- Links: 44px x 44px (or add padding)
- Form inputs: 48px height

```blade
{{-- Good: Touch-friendly button --}}
<button class="min-h-[44px] min-w-[44px] px-6 py-3 ...">
  Buy Now
</button>

{{-- Bad: Too small --}}
<button class="px-2 py-1 text-sm ...">
  Buy
</button>
```

### 2. Responsive Typography

```blade
{{-- Mobile: Smaller text --}}
<h1 class="text-xl md:text-2xl lg:text-3xl font-bold">
  {{ $product->title }}
</h1>

<p class="text-sm md:text-base text-gray-600">
  {{ $product->description }}
</p>
```

### 3. Grid Layouts

```blade
{{-- Product Grid --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
  @foreach($products as $product)
    @include('components.product-card', compact('product'))
  @endforeach
</div>
```

### 4. Mobile Navigation

```blade
{{-- Mobile Menu --}}
<div class="lg:hidden fixed inset-0 z-50 bg-white" x-show="mobileMenu">
  <div class="flex flex-col h-full">
    <div class="flex items-center justify-between p-4 border-b">
      <span class="text-lg font-semibold">Menu</span>
      <button @click="mobileMenu = false" class="p-2">
        <x-icon name="x" class="w-6 h-6" />
      </button>
    </div>
    
    <nav class="flex-1 overflow-y-auto p-4 space-y-2">
      <a href="/" class="block px-4 py-3 rounded-lg hover:bg-gray-100">Home</a>
      <a href="/explore" class="block px-4 py-3 rounded-lg hover:bg-gray-100">Explore</a>
      <a href="/wallet" class="block px-4 py-3 rounded-lg hover:bg-gray-100">Wallet</a>
    </nav>
  </div>
</div>
```

### 5. Responsive Images

```blade
<img src="{{ $product->picture_url }}"
     alt="{{ $product->title }}"
     class="w-full h-48 sm:h-56 md:h-64 object-cover rounded-lg"
     loading="lazy">
```

### 6. Sticky Elements

```blade
{{-- Sticky Mobile CTA --}}
<div class="fixed bottom-0 left-0 right-0 p-4 bg-white border-t lg:hidden">
  <button class="w-full py-4 bg-blue-600 text-white font-bold rounded-xl">
    Buy Now - {{ number_format($product->price) }} تومان
  </button>
</div>
```

## Common Responsive Patterns

### 1. Sidebar to Drawer (Mobile)

```blade
{{-- Desktop: Sidebar --}}
<aside class="hidden lg:block w-64">
  @include('components.sidebar')
</aside>

{{-- Mobile: Drawer --}}
<div class="lg:hidden">
  <button @click="sidebarOpen = true" class="p-2">
    <x-icon name="menu" class="w-6 h-6" />
  </button>
  
  <div x-show="sidebarOpen" class="fixed inset-0 z-50">
    <div class="absolute inset-0 bg-black/50" @click="sidebarOpen = false"></div>
    <div class="absolute left-0 top-0 h-full w-64 bg-white">
      @include('components.sidebar')
    </div>
  </div>
</div>
```

### 2. Horizontal to Vertical (Mobile)

```blade
{{-- Desktop: Horizontal --}}
<div class="hidden md:flex gap-4">
  <span class="px-4 py-2 bg-gray-100 rounded-full">Tag 1</span>
  <span class="px-4 py-2 bg-gray-100 rounded-full">Tag 2</span>
</div>

{{-- Mobile: Vertical --}}
<div class="md:hidden flex flex-wrap gap-2">
  <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tag 1</span>
  <span class="px-3 py-1 bg-gray-100 rounded-full text-sm">Tag 2</span>
</div>
```

### 3. Stack to Grid (Mobile)

```blade
{{-- Desktop: Side by side --}}
<div class="hidden md:flex gap-8">
  <div class="w-1/2">
    <img src="{{ $product->picture_url }}" class="rounded-lg">
  </div>
  <div class="w-1/2">
    <h1>{{ $product->title }}</h1>
    {{-- Product details --}}
  </div>
</div>

{{-- Mobile: Stacked --}}
<div class="md:hidden space-y-4">
  <img src="{{ $product->picture_url }}" class="rounded-lg w-full">
  <h1>{{ $product->title }}</h1>
  {{-- Product details --}}
</div>
```

## Testing Checklist

### Mobile (320px - 767px)
- [ ] Navigation accessible (hamburger menu)
- [ ] Text readable (min 14px)
- [ ] Buttons touch-friendly (44px min)
- [ ] Images scale properly
- [ ] Forms easy to fill
- [ ] No horizontal scroll

### Tablet (768px - 1023px)
- [ ] 2-column layouts work
- [ ] Sidebar accessible
- [ ] Images optimized
- [ ] Touch targets adequate

### Desktop (1024px+)
- [ ] Max-width container (1280px)
- [ ] Proper spacing
- [ ] Hover states work
- [ ] Keyboard navigation

## Performance on Mobile

### Image Optimization
```blade
<img src="{{ $product->picture_url }}"
     srcset="{{ $product->picture_url }} 400w,
             {{ $product->picture_url }} 800w"
     sizes="(max-width: 640px) 100vw, 
            (max-width: 1024px) 50vw, 
            33vw"
     loading="lazy"
     class="w-full h-auto">
```

### Lazy Loading
```blade
<img src="{{ $product->picture_url }}" 
     loading="lazy"
     class="w-full h-48 object-cover">
```

## Common Issues & Fixes

### Issue 1: Text Too Small on Mobile
```blade
{{-- Bad --}}
<p class="text-xs">Important text</p>

{{-- Good --}}
<p class="text-sm md:text-base">Important text</p>
```

### Issue 2: Buttons Too Close Together
```blade
{{-- Bad --}}
<div class="flex gap-1">
  <button>Buy</button>
  <button>Save</button>
</div>

{{-- Good --}}
<div class="flex gap-3">
  <button class="flex-1 py-3">Buy</button>
  <button class="py-3 px-4">Save</button>
</div>
```

### Issue 3: Images Overflow on Mobile
```blade
{{-- Bad --}}
<img src="..." class="w-full">

{{-- Good --}}
<div class="overflow-hidden rounded-lg">
  <img src="..." class="w-full h-auto">
</div>
```

## Tools for Testing

1. **Chrome DevTools** - Device emulation
2. **BrowserStack** - Real device testing
3. **Lighthouse** - Performance audit
4. **Wave** - Accessibility check

Target: Perfect experience on all devices
