---
name: pwa-setup
description: Progressive Web App configuration for mobile-like experience
disable-model-invocation: true
---

# PWA Setup

Configure Progressive Web App for mobile-like experience.

## When to Use
- When users access via mobile browsers
- For offline functionality
- For app-like experience
- For push notifications

## PWA Requirements

### 1. Web App Manifest

Create public/manifest.json with:
- name: Marketplace
- short_name: Market
- start_url: /
- display: standalone
- theme_color: #2563eb
- icons: Multiple sizes (72, 96, 128, 144, 152, 192, 384, 512)

### 2. Service Worker

Create public/sw.js with:
- Cache name: marketplace-v1
- Cache essential assets on install
- Serve from cache, fallback to network
- Offline fallback page

### 3. Layout Updates

Add to head section:
- meta theme-color
- apple-mobile-web-app-capable
- link manifest
- apple-touch-icon

Register service worker in script.

### 4. Offline Page

Create resources/views/offline.blade.php with:
- Friendly offline message
- Retry button
- App branding

## PWA Features

### 1. Home Screen Installation
- Automatic prompt on mobile
- Custom install button
- App-like experience

### 2. Offline Support
- Cache essential assets
- Show offline page
- Queue actions for sync

### 3. Push Notifications
- Order updates
- Price alerts
- Promotional messages

### 4. App-like UX
- Standalone mode
- Splash screen
- Fast loading

## Testing Checklist

### Installation
- [ ] Manifest loads correctly
- [ ] Icons display properly
- [ ] Install prompt appears
- [ ] App installs successfully

### Offline
- [ ] Essential pages cached
- [ ] Offline page displays
- [ ] Actions queue when offline
- [ ] Sync when back online

### Performance
- [ ] First load < 3 seconds
- [ ] Subsequent loads < 1 second
- [ ] Lighthouse score > 90

## Files to Create

1. [ ] public/manifest.json
2. [ ] public/sw.js
3. [ ] public/offline.blade.php
4. [ ] public/icons/ (all sizes)
5. [ ] app/Http/Middleware/OfflineMiddleware.php
6. [ ] docs/pwa-guide.md

Target: Native app-like experience on mobile
