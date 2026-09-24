---
name: perf-audit
description: Identify performance bottlenecks, N+1 queries, and caching opportunities
disable-model-invocation: true
---

# Performance Audit

Analyze and optimize application performance for production load.

## When to Use
- Before launch (critical)
- When pages load slowly
- When database queries spike
- Before high-traffic events

## Audit Areas

### 1. Database Query Analysis

**Tools:**
```bash
# Enable query logging in dev
php artisan tinker
>>> DB::enableQueryLog()

# Check for N+1 in controllers
php artisan telescope:serve
```

**Files to Check:**
- `app/Http/Controllers/ProductController.php` — Product listing
- `app/Http/Controllers/OrderController.php` — Order queries
- `app/Http/Controllers/WalletController.php` — Transaction history
- `app/Http/Controllers/Buyer/DashboardController.php` — User dashboard

**Common N+1 Issues:**
- Loading product reviews without eager loading
- Loading order items without product relation
- Loading wallet transactions without user relation

### 2. Eager Loading Opportunities

```php
// Bad: N+1 query
$orders = Order::where('user_id', $userId)->get();
foreach ($orders as $order) {
    echo $order->product->title; // Separate query each time
}

// Good: Eager loading
$orders = Order::with('product')
    ->where('user_id', $userId)
    ->get();
```

**Check these relationships:**
- Order → Product (seller, category)
- Product → Reviews (with user)
- WalletTransaction → Order
- User → Products (for seller dashboard)

### 3. Missing Indexes

**Critical indexes to add:**
```php
// In migration
Schema::table('orders', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('product_id');
    $table->index('status');
    $table->index(['user_id', 'status']);
});

Schema::table('wallet_transactions', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('order_id');
    $table->index('created_at');
});

Schema::table('products', function (Blueprint $table) {
    $table->index('seller_id');
    $table->index('category_id');
    $table->index('status');
    $table->index('created_at');
});
```

### 4. Caching Strategy

**Cache these queries:**
```php
// Product listings (cache 5 minutes)
$products = Cache::remember('products:page:'.$page, 300, function () {
    return Product::where('status', 'active')
        ->with(['seller:id,username', 'category:id,name'])
        ->latest()
        ->paginate(20);
});

// Category list (cache 1 hour)
$categories = Cache::remember('categories', 3600, function () {
    return Category::all();
});

// User wallet balance (cache 1 minute, invalidate on transaction)
$balance = Cache::remember('user:'.$userId.':balance', 60, function () use ($userId) {
    return User::find($userId)->wallet_balance;
});
```

### 5. N+1 in Views

**Check Blade templates for:**
- `@foreach` loops with relations
- `{{ $item->relation->property }}` patterns
- Lazy loading in pagination

### 6. API Response Optimization

**For product listing API:**
- Use API Resources for consistent structure
- Paginate results (max 50 per page)
- Select only needed columns
- Cache frequently accessed data

### 7. File Upload Performance

**Current setup:**
- Products: local disk (fast)
- Receipts: local disk (fast)
- Avatars: local disk (fast)

**Optimize:**
- Generate thumbnails for product images
- Use queue for image processing
- Implement lazy loading in views

## Performance Metrics to Track

1. **Page Load Times:**
   - Homepage: < 2s
   - Product listing: < 1.5s
   - Product detail: < 1s
   - Checkout: < 2s

2. **Database Queries:**
   - Average queries per page: < 15
   - Total query time per page: < 100ms

3. **Cache Hit Ratio:**
   - Target: > 80%
   - Monitor: Redis INFO stats

## Files to Optimize

Priority order:
1. [ ] ProductController@show (product detail page)
2. [ ] ProductController@index (explore page)
3. [ ] OrderController@store (purchase flow)
4. [ ] WalletController@index (wallet history)
5. [ ] Buyer\DashboardController@index (dashboard)

## Quick Wins

1. **Add eager loading** to all relationship queries
2. **Cache category list** (rarely changes)
3. **Index foreign keys** (orders, wallet_transactions)
4. **Paginate all lists** (avoid loading all records)
5. **Use select()** to fetch only needed columns

## Monitoring Commands

```bash
# Enable query log in development
php artisan telescope:serve

# Check slow queries
tail -f storage/logs/laravel.log | grep "slow"

# Monitor Redis cache
redis-cli INFO stats

# Check database connections
php artisan db:monitor
```
