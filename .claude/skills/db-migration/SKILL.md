---
name: db-migration
description: Safe database migration workflows, optimization, and backup strategies
disable-model-invocation: true
---

# Database Migration & Optimization

Safe database operations for production marketplace.

## When to Use
- Adding new features requiring schema changes
- Optimizing existing queries
- Setting up backup strategies
- Data cleanup and maintenance

## Migration Best Practices

### 1. Always Use Rollback-Friendly Migrations

```php
// Good: Reversible migration
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->index('status');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropIndex('orders_status_index');
    });
}
```

### 2. Test Migrations First

```bash
# Dry run to see SQL
php artisan migrate --pretend

# Test on staging
php artisan migrate --force

# Verify
php artisan migrate:status
```

### 3. Zero-Downtime Migrations

**For large tables:**
```php
// Step 1: Add column as nullable
Schema::table('users', function (Blueprint $table) {
    $table->string('phone_verified_at')->nullable();
});

// Step 2: Backfill data
User::whereNull('phone_verified_at')
    ->update(['phone_verified_at' => now()]);

// Step 3: Make NOT NULL (after deploy)
Schema::table('users', function (Blueprint $table) {
    $table->string('phone_verified_at')->nullable(false)->change();
});
```

## Critical Migrations Needed

### 1. Add Missing Indexes

```php
// database/migrations/xxxx_add_indexes_to_orders.php
public function up()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->index('user_id');
        $table->index(['user_id', 'status']);
        $table->index('created_at');
    });
}

public function down()
{
    Schema::table('orders', function (Blueprint $table) {
        $table->dropIndex('orders_user_id_index');
        $table->dropIndex('orders_user_id_status_index');
        $table->dropIndex('orders_created_at_index');
    });
}
```

### 2. Wallet Transactions Index

```php
public function up()
{
    Schema::table('wallet_transactions', function (Blueprint $table) {
        $table->index('user_id');
        $table->index('order_id');
        $table->index(['user_id', 'created_at']);
    });
}
```

### 3. Products Optimization

```php
public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->index('seller_id');
        $table->index('category_id');
        $table->index('status');
        $table->index(['status', 'created_at']);
    });
}
```

## Backup Strategy

### 1. Automated Backups

```bash
# Daily backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > backups/db_$DATE.sql.gz

# Keep 30 days of backups
find backups/ -name "*.sql.gz" -mtime +30 -delete
```

### 2. Backup Schedule

- **Daily:** Full database backup
- **Hourly:** Transaction log backup (if using InnoDB)
- **Weekly:** Backup verification test

### 3. Restore Procedure

```bash
# Restore from backup
gunzip < backups/db_20240101_020000.sql.gz | mysql -u $DB_USER -p$DB_PASS $DB_NAME

# Verify restoration
php artisan migrate:status
php artisan tinker
>>> User::count()
```

## Data Cleanup Scripts

### 1. Old Sessions

```php
// database/migrations/xxxx_cleanup_sessions.php
public function up()
{
    DB::table('sessions')
        ->where('last_activity', '<', now()->subDays(30))
        ->delete();
}
```

### 2. Expired Password Resets

```php
DB::table('password_resets')
    ->where('created_at', '<', now()->subHours(24))
    ->delete();
```

### 3. Old Activity Logs

```php
DB::table('activity_log')
    ->where('created_at', '<', now()->subDays(90))
    ->delete();
```

## Performance Monitoring

### 1. Query Analysis

```bash
# Enable slow query log
mysql -e "SET GLOBAL slow_query_log = 'ON';"
mysql -e "SET GLOBAL long_query_time = 2;"

# Check index usage
mysql -e "SHOW INDEX FROM orders;"
```

### 2. Table Statistics

```php
// Check table sizes
Schema::getConnection()->getDoctrineSchemaManager()->listTableDetails('orders');

// Monitor row counts
DB::select("SELECT TABLE_ROWS FROM INFORMATION_SCHEMA.TABLES 
            WHERE TABLE_NAME = 'orders'");
```

## Migration Checklist

For each migration:
- [ ] Tested on development database
- [ ] Rollback method implemented
- [ ] No data loss scenarios
- [ ] Performance impact assessed
- [ ] Backup taken before running
- [ ] Monitoring alerts configured

## Files to Create

1. [ ] `database/migrations/xxxx_add_indexes_to_orders.php`
2. [ ] `database/migrations/xxxx_add_indexes_to_wallet_transactions.php`
3. [ ] `database/migrations/xxxx_add_indexes_to_products.php`
4. [ ] `database/seeders/ProductionSeeder.php` (minimal test data)
5. [ ] `scripts/backup.sh`
6. [ ] `scripts/cleanup.php`
