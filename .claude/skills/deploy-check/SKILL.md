---
name: deploy-check
description: Pre-deployment validation checklist for production readiness
disable-model-invocation: true
---

# Deployment Checklist

Validate all systems are ready for production deployment.

## Pre-Deployment Checks

### 1. Environment Configuration
```bash
# Verify .env exists and is configured
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Checklist:
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] APP_URL configured with HTTPS
- [ ] Database credentials secure
- [ ] CACHE_DRIVER configured (Redis recommended)
- [ ] QUEUE_CONNECTION configured
- [ ] MAIL_ settings configured
- [ ] SMS/Ntfy credentials configured

### 2. Database Readiness
```bash
# Check migrations status
php artisan migrate:status

# Verify no pending migrations
php artisan migrate --pretend
```

Checklist:
- [ ] All migrations run
- [ ] Database backups configured
- [ ] indexes created for wallet_balance, orders.status
- [ ] Foreign key constraints enabled

### 3. File Storage Security
```bash
# Verify storage links and permissions
php artisan storage:link
ls -la storage/app/
```

Checklist:
- [ ] storage/app/ not publicly accessible
- [ ] storage/app/public/ only for truly public files
- [ ] storage/app/products/ exists (local disk)
- [ ] storage/app/receipts/ exists (local disk)
- [ ] storage/app/avatars/ exists (local disk)
- [ ] Signed routes configured for file access

### 4. Queue & Workers
```bash
# Check queue driver
php artisan queue:work --dry-run
```

Checklist:
- [ ] Queue worker running (supervisor/systemd)
- [ ] Failed job table created
- [ ] Job retry logic configured
- [ ] Mail/notifications queue configured

### 5. Cache Configuration
```bash
php artisan cache:clear
php artisan config:cache
```

Checklist:
- [ ] Redis/Memcached configured
- [ ] Cache prefix set (avoid collisions)
- [ ] Session cache configured
- [ ] View cache enabled

### 6. Security Verification
```bash
# Run security checks
php artisan route:list --middleware=auth
```

Checklist:
- [ ] All sensitive routes behind auth middleware
- [ ] Rate limiting on login (throttle:5,1)
- [ ] HTTPS enforced
- [ ] CSP headers configured
- [ ] CORS configured if needed

### 7. File Permissions
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

Checklist:
- [ ] storage/ writable by web server
- [ ] bootstrap/cache/ writable
- [ ] .env readable only by web server user

### 8. Monitoring Setup
Checklist:
- [ ] Error tracking (Sentry/Flare)
- [ ] Application monitoring
- [ ] Log rotation configured
- [ ] Health check endpoint

## Post-Deployment Verification

1. **Smoke Test:**
   - [ ] Homepage loads
   - [ ] Login works
   - [ ] Product listing loads
   - [ ] Wallet balance displays

2. **Critical Paths:**
   - [ ] User registration
   - [ ] Product purchase flow
   - [ ] Wallet deposit
   - [ ] File download (signed URL)

3. **Error Monitoring:**
   - [ ] No 500 errors in logs
   - [ ] Queue processing normally
   - [ ] Cache hit ratio healthy

## Rollback Plan
- [ ] Database backup before migration
- [ ] Old code tagged in git
- [ ] Rollback script ready
- [ ] Communication plan for users
