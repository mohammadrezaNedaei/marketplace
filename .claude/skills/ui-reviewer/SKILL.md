---
name: ui-reviewer
description: Review Blade templates for design consistency, visual hierarchy, and UI best practices
disable-model-invocation: true
---

# UI Reviewer

Analyze Blade templates for design quality, consistency, and visual excellence.

## When to Use
- After creating/modifying Blade templates
- Before deploying new features
- During design reviews
- When updating UI components

## Design Audit Checklist

### 1. Visual Hierarchy

**Check for:**
- [ ] Clear heading structure (H1 → H2 → H3)
- [ ] Proper font sizes (body: 16px, headings: scale by 1.25x)
- [ ] Adequate spacing (8px grid system)
- [ ] Color contrast ratios (WCAG AA: 4.5:1 for text)
- [ ] Visual weight guides eye to CTA

**Example Fix:**
```blade
{{-- Bad: No hierarchy --}}
<div>
  <p class="text-lg">Product Title</p>
  <p>Description here</p>
</div>

{{-- Good: Clear hierarchy --}}
<div class="space-y-2">
  <h1 class="text-2xl font-bold text-gray-900">{{ $product->title }}</h1>
  <p class="text-base text-gray-600 leading-relaxed">{{ $product->description }}</p>
</div>
```

### 2. Spacing & Layout

**8px Grid System:**
- XS: 4px (0.25rem)
- SM: 8px (0.5rem)
- MD: 16px (1rem)
- LG: 24px (1.5rem)
- XL: 32px (2rem)
- 2XL: 48px (3rem)

**Check for:**
- [ ] Consistent vertical rhythm
- [ ] Proper padding on cards (16-24px)
- [ ] Margin between sections (32-48px)
- [ ] Gutters in grid layouts (16-24px)

### 3. Color System

**Primary Colors:**
```css
--primary-50: #eff6ff;
--primary-500: #3b82f6;
--primary-600: #2563eb;
--primary-700: #1d4ed8;
```

**Neutral Colors:**
```css
--gray-50: #f9fafb;
--gray-100: #f3f4f6;
--gray-900: #111827;
```

**Check for:**
- [ ] Consistent use of primary color for CTAs
- [ ] Gray scale for text (gray-900 for headings, gray-600 for body)
- [ ] Status colors (green-500 for success, red-500 for error)
- [ ] No random colors

### 4. Typography

**Font Stack:**
```css
font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
```

**Check for:**
- [ ] Line height: 1.5 for body, 1.2 for headings
- [ ] Letter spacing: -0.025rem for headings
- [ ] Font weights: 400 (body), 600 (semibold), 700 (bold)
- [ ] Max line length: 60-80 characters

### 5. Component Consistency

**Buttons:**
```blade
{{-- Primary Button --}}
<button class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg 
               hover:bg-blue-700 transition-colors duration-200 
               focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
  {{ $label }}
</button>

{{-- Secondary Button --}}
<button class="px-6 py-3 bg-white text-gray-700 font-semibold rounded-lg 
               border border-gray-300 hover:bg-gray-50 transition-colors duration-200">
  {{ $label }}
</button>
```

**Cards:**
```blade
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 
            hover:shadow-md transition-shadow duration-200">
  {{ $content }}
</div>
```

**Input Fields:**
```blade
<input type="text" 
       class="w-full px-4 py-3 border border-gray-300 rounded-lg 
              focus:ring-2 focus:ring-blue-500 focus:border-blue-500 
              transition-colors duration-200
              placeholder-gray-400"
       placeholder="{{ $placeholder }}">
```

### 6. Interactive States

**Check for:**
- [ ] Hover states on all clickable elements
- [ ] Focus states for keyboard navigation
- [ ] Active/pressed states for buttons
- [ ] Loading states for async actions
- [ ] Disabled states with proper styling

### 7. Empty States

**Template:**
```blade
@if($items->isEmpty())
  <div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
      <x-icon name="inbox" class="w-8 h-8 text-gray-400" />
    </div>
    <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $emptyTitle }}</h3>
    <p class="text-gray-600 max-w-sm">{{ $emptyMessage }}</p>
    @if($actionUrl)
      <a href="{{ $actionUrl }}" class="mt-4 text-blue-600 hover:text-blue-700 font-medium">
        {{ $actionLabel }}
      </a>
    @endif
  </div>
@endif
```

## Review Process

### Step 1: Layout Analysis
```bash
# Check all Blade templates
find resources/views -name "*.blade.php" | head -20
```

### Step 2: Component Inventory
- List all reusable components
- Check for consistency
- Identify missing components

### Step 3: Design Token Verification
- Colors match design system
- Spacing follows 8px grid
- Typography is consistent

### Step 4: Responsive Check
- Mobile-first approach
- Breakpoints: sm(640), md(768), lg(1024), xl(1280)
- Touch targets: min 44px

### Step 5: Accessibility Check
- Color contrast
- Keyboard navigation
- Screen reader support

## Files to Audit

Priority order:
1. [ ] resources/views/layouts/app.blade.php
2. [ ] resources/views/home.blade.php
3. [ ] resources/views/products/show.blade.php
4. [ ] resources/views/wallet/index.blade.php
5. [ ] resources/views/orders/show.blade.php

## Common Issues to Fix

### Issue 1: Inconsistent Spacing
```blade
{{-- Bad --}}
<div class="p-4 mb-2">
<div class="p-6 mb-4">

{{-- Good --}}
<div class="p-6 mb-4">
<div class="p-6 mb-4">
```

### Issue 2: Missing Hover States
```blade
{{-- Bad --}}
<a href="/products">View All</a>

{{-- Good --}}
<a href="/products" class="text-blue-600 hover:text-blue-700 font-medium transition-colors">
  View All
</a>
```

### Issue 3: Poor Color Contrast
```blade
{{-- Bad: Fails WCAG --}}
<p class="text-gray-400">Important text</p>

{{-- Good: Passes WCAG --}}
<p class="text-gray-600">Important text</p>
```

## Output Format

For each template reviewed:
1. List issues found
2. Provide code fixes
3. Show before/after
4. Rate overall quality (1-5)

## Score Card

| Category | Weight | Score |
|----------|--------|-------|
| Visual Hierarchy | 20% | ?/5 |
| Spacing & Layout | 20% | ?/5 |
| Color System | 15% | ?/5 |
| Typography | 15% | ?/5 |
| Component Consistency | 15% | ?/5 |
| Interactive States | 10% | ?/5 |
| Empty States | 5% | ?/5 |
| **Overall** | 100% | ?/5 |

Target: 4.5/5 before production
