---
name: frontend-design
description: Design system, component patterns, and visual design best practices
disable-model-invocation: true
---

# Frontend Design System

Comprehensive design system for consistent, beautiful interfaces.

## Design Tokens

### Colors

**Primary (Blue)**
- --blue-50: #eff6ff
- --blue-500: #3b82f6
- --blue-600: #2563eb
- --blue-700: #1d4ed8

**Success (Green)**
- --green-500: #22c55e
- --green-600: #16a34a

**Warning (Amber)**
- --amber-500: #f59e0b
- --amber-600: #d97706

**Error (Red)**
- --red-500: #ef4444
- --red-600: #dc2626

**Neutrals**
- --gray-50: #f9fafb
- --gray-100: #f3f4f6
- --gray-500: #6b7280
- --gray-600: #4b5563
- --gray-900: #111827

### Typography

Font: Inter, -apple-system, BlinkMacSystemFont, sans-serif

Scale:
- text-xs: 12px
- text-sm: 14px
- text-base: 16px
- text-lg: 18px
- text-xl: 20px
- text-2xl: 24px
- text-3xl: 30px

### Spacing (8px Grid)

- space-1: 4px
- space-2: 8px
- space-3: 12px
- space-4: 16px
- space-6: 24px
- space-8: 32px
- space-12: 48px

### Border Radius

- radius-sm: 4px
- radius-md: 8px
- radius-lg: 12px
- radius-xl: 16px
- radius-full: 9999px

## Component Patterns

### Buttons

Primary: px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700

Secondary: px-6 py-3 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50

Danger: px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700

### Cards

Basic: bg-white rounded-xl shadow-sm border border-gray-100 p-6

Interactive: Add hover:shadow-md hover:border-gray-200 transition-all

Product: Add overflow-hidden, image with group-hover:scale-105

### Form Inputs

Text: w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500

Label: block text-sm font-medium text-gray-700

### Badges

Success: bg-green-100 text-green-800

Warning: bg-amber-100 text-amber-800

Error: bg-red-100 text-red-800

Info: bg-blue-100 text-blue-800

## Animation Patterns

Transitions: transition-all duration-200

Scale on hover: hover:scale-105

Fade in: animate-fade-in (0.3s ease-out)

Loading: animate-pulse for skeleton loaders

## Dark Mode

Use dark: prefix for dark mode variants

bg-white dark:bg-gray-800

text-gray-900 dark:text-gray-100

## Files to Create

1. [ ] resources/css/design-tokens.css
2. [ ] resources/views/components/ui/button.blade.php
3. [ ] resources/views/components/ui/card.blade.php
4. [ ] resources/views/components/ui/input.blade.php
5. [ ] resources/views/components/ui/badge.blade.php
6. [ ] resources/views/components/ui/alert.blade.php
7. [ ] docs/design-system.md

## Design Principles

1. **Consistency** - Same patterns everywhere
2. **Clarity** - Clear visual hierarchy
3. **Simplicity** - Less is more
4. **Accessibility** - Usable by everyone
5. **Delight** - Small touches that wow

Target: Beautiful, consistent, accessible design
