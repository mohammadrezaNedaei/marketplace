---
name: seller-onboarding
description: Validate seller setup, product listings, and marketplace compliance
disable-model-invocation: true
---

# Seller Onboarding Validation

Ensure sellers are properly set up and products meet marketplace standards.

## When to Use
- When new sellers register
- Before products go live
- During seller profile reviews
- For quality assurance checks

## Seller Profile Validation

### 1. Profile Completeness Check

```php
public function validateSellerProfile(Seller $seller): array
{
    $issues = [];

    if (empty($seller->username)) {
        $issues[] = 'Username is required';
    }

    if (empty($seller->bio)) {
        $issues[] = 'Bio is recommended for better visibility';
    }

    if (empty($seller->avatar)) {
        $issues[] = 'Avatar increases buyer trust';
    }

    return $issues;
}
```

### 2. Business Information

Required for payment processing:
- [ ] Card number for withdrawals
- [ ] Cardholder name
- [ ] Phone number verified

### 3. Trust Signals

- [ ] Profile completion > 80%
- [ ] At least one product listed
- [ ] Response time < 24 hours
- [ ] Positive reviews (if any)

## Product Listing Validation

### 1. Content Requirements

```php
public function validateProduct(Product $product): array
{
    $issues = [];

    if (strlen($product->title) < 10) {
        $issues[] = 'Title must be at least 10 characters';
    }

    if (strlen($product->description) < 50) {
        $issues[] = 'Description must be at least 50 characters';
    }

    if ($product->price <= 0) {
        $issues[] = 'Price must be greater than 0';
    }

    if ($product->discount_price >= $product->price) {
        $issues[] = 'Discount price must be less than regular price';
    }

    return $issues;
}
```

### 2. File Validation

```php
public function validateProductFiles(Product $product): array
{
    $issues = [];

    // Check main image
    if (empty($product->picture_url)) {
        $issues[] = 'Product image is required';
    }

    // Check file exists
    if (!empty($product->picture_url) && !Storage::disk('local')->exists($product->picture_url)) {
        $issues[] = 'Product image file is missing';
    }

    // Check file size (max 5MB)
    if (!empty($product->picture_url)) {
        $size = Storage::disk('local')->size($product->picture_url);
        if ($size > 5 * 1024 * 1024) {
            $issues[] = 'Product image must be under 5MB';
        }
    }

    return $issues;
}
```

### 3. Digital Product Validation

For downloadable products:
- [ ] File exists on local disk
- [ ] File size reasonable (< 100MB)
- [ ] File type appropriate (zip, pdf, etc.)
- [ ] File not corrupted (can be opened)

### 4. Pricing Rules

```php
public function validatePricing(Product $product): array
{
    $issues = [];

    if ($product->price < 1000) { // Minimum 1000 Tomans
        $issues[] = 'Minimum price is 1000 Tomans';
    }

    if ($product->price > 100000000) { // Maximum 100M Tomans
        $issues[] = 'Maximum price is 100,000,000 Tomans';
    }

    if ($product->discount_price && $product->discount_price < 1000) {
        $issues[] = 'Discount price must be at least 1000 Tomans';
    }

    return $issues;
}
```

## Category Compliance

### 1. Category Selection

- [ ] Product assigned to correct category
- [ ] Category exists and is active
- [ ] Category-specific requirements met

### 2. Prohibited Content

- [ ] No illegal items
- [ ] No copyrighted material (without license)
- [ ] No harmful content
- [ ] No misleading descriptions

## Quality Assurance Checklist

### Before Product Goes Live

- [ ] Title is clear and descriptive
- [ ] Description is detailed and accurate
- [ ] Price is competitive
- [ ] Image is high quality
- [ ] Files are accessible
- [ ] Category is appropriate
- [ ] No spelling/grammar errors

### After Product Goes Live

- [ ] Product appears in search
- [ ] Product detail page loads correctly
- [ ] Files can be downloaded (after purchase)
- [ ] Reviews can be submitted
- [ ] Seller can edit product

## Seller Dashboard Metrics

Track these for seller health:
1. **Response Time:** < 24 hours
2. **Product Quality Score:** > 4.0/5.0
3. **Sale Completion Rate:** > 95%
4. **Refund Rate:** < 5%
5. **Review Rating:** > 4.0/5.0

## Automated Checks

### Product Moderation Queue

```php
// Auto-flag products for review
$products = Product::where('status', 'active')
    ->where(function ($q) {
        $q->where('title', 'like', '%spam%')
          ->orWhere('price', '<', 1000)
          ->orWhereNull('picture_url');
    })
    ->get();

foreach ($products as $product) {
    $product->status = 'pending_review';
    $product->save();
}
```

### Seller Performance Alerts

```php
// Alert on poor performance
$sellers = User::where('role', 'seller')
    ->withCount(['products as product_count'])
    ->withAvg('reviews as avg_rating')
    ->having('avg_rating', '<', 3.0)
    ->get();

// Send notification to admin
```

## Files to Create

1. [ ] `app/Services/SellerValidator.php`
2. [ ] `app/Services/ProductValidator.php`
3. [ ] `app/Http/Requests/StoreProductRequest.php` (enhanced validation)
4. [ ] `app/Http/Requests/UpdateSellerProfileRequest.php`
5. [ ] `tests/Feature/SellerOnboardingTest.php`
6. [ ] `docs/seller-guide.md`
