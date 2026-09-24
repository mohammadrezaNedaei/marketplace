---
name: ux-flow-optimizer
description: Analyze and optimize user flows for conversion, usability, and delightful experiences
disable-model-invocation: true
---

# UX Flow Optimizer

Analyze and improve user journeys for maximum conversion and satisfaction.

## When to Use
- Before launching new features
- When conversion rates are low
- During user testing
- When redesigning flows

## Core User Flows to Optimize

### 1. Registration Flow

**Current State:**
Landing -> Register -> Verify Phone -> Login -> Dashboard

**Optimization Points:**
- Add social login (Google, Apple)
- Reduce form fields to minimum
- Add progress indicator
- Show benefits above the fold
- Auto-detect country from IP

### 2. Product Browse to Purchase

**Current Flow:**
Explore -> Product Detail -> Add to Cart -> Checkout -> Payment -> Success

**Optimized Flow:**
Explore -> Product Detail -> Buy Now (one-click) -> Success

**Conversion Optimization:**
- Add urgency (stock count, time limit)
- Show social proof (reviews, purchases)
- Quick view modal
- Sticky add-to-cart on mobile
- Guest checkout option

### 3. Wallet Deposit Flow

**Current Flow:**
Wallet -> Choose Amount -> Enter Card Info -> Verify -> Success

**Optimized Flow:**
Wallet -> One-Tap Amount -> Confirm -> Success (3 seconds)

**UX Improvements:**
- Preset amount buttons
- Custom amount input
- Save card for future
- One-tap for returning users
- Progress indicator

### 4. Checkout Flow Optimization

**Current: 5 steps**
Cart -> Shipping -> Payment -> Review -> Confirm

**Optimized: 2 steps**
Review -> Confirm (with saved info)

**Conversion Boosters:**
- Show trust badges
- Display money-back guarantee
- Add urgency timer
- Show real-time validation
- Auto-apply wallet balance

## Metrics to Track

### Conversion Metrics
1. **Registration Completion Rate:** Target > 70%
2. **Browse to Purchase Rate:** Target > 15%
3. **Cart Abandonment Rate:** Target < 30%
4. **Checkout Completion Rate:** Target > 80%

### Engagement Metrics
1. **Time on Site:** Target > 3 minutes
2. **Pages per Session:** Target > 5
3. **Return Visit Rate:** Target > 40%
4. **Review Submission Rate:** Target > 20%

### Performance Metrics
1. **Page Load Time:** Target < 2 seconds
2. **Time to Interactive:** Target < 3 seconds
3. **First Input Delay:** Target < 100ms

## A/B Testing Ideas

### Test 1: CTA Button Color
- Version A: Blue (current)
- Version B: Green (urgency)
- Version C: Orange (energy)

### Test 2: Product Grid Layout
- Version A: 2 columns (mobile)
- Version B: 1 column (mobile)
- Version C: Masonry layout

### Test 3: Pricing Display
- Version A: Price only
- Version B: Price + strikethrough
- Version C: Price + savings percentage

## Accessibility Requirements

### Keyboard Navigation
- Tab order logical
- Focus visible on all interactive elements
- Enter submits forms
- Escape closes modals

### Screen Reader Support
- ARIA labels on buttons
- Alt text on images
- Semantic HTML structure
- Form field labels

### Color and Contrast
- Text contrast ratio > 4.5:1
- Don't rely on color alone
- Focus indicators visible

## Files to Create

1. [ ] app/View/Components/QuickBuy.php
2. [ ] app/View/Components/DepositWidget.php
3. [ ] resources/views/components/product-card.blade.php
4. [ ] resources/views/components/checkout.blade.php
5. [ ] resources/views/livewire/product-grid.blade.php
6. [ ] tests/Feature/UX/FlowOptimizationTest.php

## Quick Wins

1. **Add "Buy Now" button** - Skip cart, direct purchase
2. **Preset deposit amounts** - One-tap wallet funding
3. **Sticky mobile CTA** - Always visible purchase button
4. **Progress indicators** - Show steps remaining
5. **Trust signals** - Security badges, guarantees

Target: 20% increase in conversion rate
