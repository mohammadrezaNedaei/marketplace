---
name: health-check
description: Verify all services and dependencies are running correctly
disable-model-invocation: true
---

# Health Check

Monitor application health and dependencies.

## When to Use
- After deployment
- During monitoring setup
- When debugging issues
- Periodic health checks

## Health Check Endpoints

### 1. Application Health

Create `/health` endpoint:

```php
// routes/web.php
Route::get('/health', function () {
    $checks = [
        'app' => 'ok',
        'database' => CheckDatabase::check(),
        'cache' => CheckCache::check(),
        'queue' => CheckQueue::check(),
        'storage' => CheckStorage::check(),
    ];
    
    $healthy = !in_array('error', $checks);
    
    return response()->json([
        'status' => $healthy ? 'healthy' : 'degraded',
        'checks' => $checks,
        'timestamp' => now()->toISOString(),
    ], $healthy ? 200 : 503);
});
```

### 2. Database Health

```php
class CheckDatabase
{
    public static function check(): string
    {
        try {
            DB::connection()->getPdo();
            return 'ok';
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }
}
```

### 3. Cache Health

```php
class CheckCache
{
    public static function check(): string
    {
        try {
            Cache::put('health_check', 'ok', 10);
            return Cache::get('health_check') === 'ok' ? 'ok' : 'error';
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }
}
```

### 4. Queue Health

```php
class CheckQueue
{
    public static function check(): string
    {
        try {
            $size = Queue::size();
            return $size < 1000 ? 'ok' : 'warning: queue size ' . $size;
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }
}
```

### 5. Storage Health

```php
class CheckStorage
{
    public static function check(): string
    {
        try {
            $testFile = 'health_check_' . time();
            Storage::disk('local')->put($testFile, 'test');
            Storage::disk('local')->delete($testFile);
            return 'ok';
        } catch (Exception $e) {
            return 'error: ' . $e->getMessage();
        }
    }
}
```

## CLI Health Check Script

```bash
#!/bin/bash
# scripts/health-check.sh

echo "Running health checks..."

# Check PHP
php -v > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✓ PHP is running"
else
    echo "✗ PHP is not available"
fi

# Check Laravel
php artisan --version > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✓ Laravel is configured"
else
    echo "✗ Laravel configuration error"
fi

# Check database connection
php artisan db:monitor > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✓ Database connection OK"
else
    echo "✗ Database connection failed"
fi

# Check queue worker
pgrep -f "queue:work" > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✓ Queue worker is running"
else
    echo "✗ Queue worker is not running"
fi

# Check Redis
redis-cli ping > /dev/null 2>&1
if [ $? -eq 0 ]; then
    echo "✓ Redis is running"
else
    echo "✗ Redis is not available"
fi

echo "Health check complete."
```

## Monitoring Dashboard

Track these metrics:
1. **Response Time:** < 500ms average
2. **Error Rate:** < 1%
3. **Queue Size:** < 1000 jobs
4. **Memory Usage:** < 80%
5. **CPU Usage:** < 70%

## Alerting Rules

Alert when:
- Database connection fails
- Queue size > 1000
- Response time > 2s
- Error rate > 5%
- Memory usage > 90%

## Files to Create

1. [ ] app/Http/Controllers/HealthController.php
2. [ ] app/Services/Health/CheckDatabase.php
3. [ ] app/Services/Health/CheckCache.php
4. [ ] app/Services/Health/CheckQueue.php
5. [ ] app/Services/Health/CheckStorage.php
6. [ ] routes/health.php
7. [ ] scripts/health-check.sh
8. [ ] config/health.php
