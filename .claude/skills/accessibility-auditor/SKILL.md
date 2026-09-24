---
name: accessibility-auditor
description: WCAG 2.1 compliance checking for accessible interfaces
disable-model-invocation: true
---

# Accessibility Auditor

Ensure WCAG 2.1 AA compliance for all users.

## When to Use
- After creating/modifying templates
- Before deployment (legal requirement)
- During accessibility audits

## WCAG 2.1 AA Checklist

### 1. Perceivable

**Text Alternatives**
- All images need descriptive alt text
- Decorative images use alt=""
- Icon buttons need aria-label

**Adaptable Content**
- Proper heading hierarchy (H1 > H2 > H3)
- Semantic HTML (nav, main, article, aside)
- Form fields have labels

**Distinguishable**
- Color contrast ratio >= 4.5:1
- Dont rely on color alone
- Focus indicators visible

### 2. Operable

**Keyboard Accessible**
- All interactive elements focusable
- Skip navigation link provided
- Focus order logical
- No keyboard traps

**Enough Time**
- Session timeout warnings
- Auto-playing content can be paused

**Seizure Safe**
- No flashing content (3 flashes max)
- Respect prefers-reduced-motion

**Navigable**
- Descriptive link text (not "Click Here")
- Clear page titles

### 3. Understandable

**Readable**
- Specify page language (lang attribute)
- Abbreviations have expansions

**Predictable**
- Consistent navigation
- Consistent identification

**Input Assistance**
- Explicit labels for all inputs
- Error messages descriptive
- Required fields marked

### 4. Robust

**Compatible**
- Valid HTML
- ARIA landmarks used
- Live regions for dynamic content

## Common Issues and Fixes

### Issue 1: Missing Alt Text
```blade
{{-- Bad --}}
<img src="{{ $url }}">

{{-- Good --}}
<img src="{{ $url }}" alt="{{ $description }}">
```

### Issue 2: No Focus Indicators
```blade
{{-- Bad --}}
<button class="focus:outline-none">Click</button>

{{-- Good --}}
<button class="focus:ring-2 focus:ring-blue-500">Click</button>
```

### Issue 3: Poor Color Contrast
```blade
{{-- Bad: ratio < 4.5:1 --}}
<p class="text-gray-400">Text</p>

{{-- Good: ratio >= 4.5:1 --}}
<p class="text-gray-600">Text</p>
```

### Issue 4: Missing Form Labels
```blade
{{-- Bad --}}
<input type="email" placeholder="Email">

{{-- Good --}}
<label for="email">Email</label>
<input type="email" id="email">
```

### Issue 5: No Skip Navigation
```blade
<a href="#main" class="sr-only focus:not-sr-only">
  Skip to main content
</a>
<main id="main" tabindex="-1">
  Content
</main>
```

## ARIA Patterns

### Modal Dialog
- role="dialog"
- aria-modal="true"
- aria-labelledby for title

### Accordion
- @click toggles aria-expanded
- aria-controls links to panel
- role="region" on panel

### Tabs
- role="tablist" on container
- role="tab" on triggers
- role="tabpanel" on content

## Testing Checklist

### Keyboard
- [ ] All elements focusable
- [ ] Focus order logical
- [ ] Focus visible
- [ ] No keyboard traps
- [ ] Enter activates buttons
- [ ] Escape closes modals

### Screen Reader
- [ ] Images have alt text
- [ ] Forms have labels
- [ ] Headings hierarchical
- [ ] Landmarks present

### Visual
- [ ] Contrast >= 4.5:1
- [ ] Text resizable to 200%
- [ ] Focus indicators visible
- [ ] Reflow at 320px

## Testing Tools

1. Lighthouse (Chrome DevTools)
2. axe DevTools (browser extension)
3. WAVE (web evaluator)
4. NVDA/VoiceOver (screen readers)

## Files to Create

1. [ ] resources/views/components/accessibility/skip-nav.blade.php
2. [ ] resources/views/components/accessibility/sr-only.blade.php
3. [ ] tests/Feature/AccessibilityTest.php
4. [ ] docs/accessibility-guide.md

Target: WCAG 2.1 AA compliance
