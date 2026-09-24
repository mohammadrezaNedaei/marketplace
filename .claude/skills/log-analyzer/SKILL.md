---
name: log-analyzer
description: Parse error logs, identify critical issues, and alert on anomalies
disable-model-invocation: true
---

# Log Analyzer

Intelligent log analysis for proactive issue detection.

## When to Use
- When debugging production issues
- During incident response
- For proactive monitoring
- When optimizing performance

## Log Analysis Scripts

### 1. Error Log Parser

```bash
#!/bin/bash
# scripts/analyze-errors.sh

LOG_FILE="storage/logs/laravel.log"
REPORT_FILE="storage/logs/error-report-$(date +%Y%m%d).txt"

echo "=== Error Log Analysis ===" > $REPORT_FILE
echo "Date: $(date)" >> $REPORT_FILE
echo "" >> $REPORT_FILE

# Count errors by type
echo "Error Summary:" >> $REPORT_FILE
grep -c "ERROR" $LOG_FILE >> $REPORT_FILE
grep -c "CRITICAL" $LOG_FILE >> $REPORT_FILE
grep -c "ALERT" $LOG_FILE >> $REPORT_FILE
grep -c "EMERGENCY" $LOG_FILE >> $REPORT_FILE
echo "" >> $REPORT_FILE

# Top error messages
echo "Top 10 Error Messages:" >> $REPORT_FILE
grep "ERROR" $LOG_FILE | awk -F': ' '{print $2}' | sort | uniq -c | sort -rn | head -10 >> $REPORT_FILE
echo "" >> $REPORT_FILE

# Recent errors (last hour)
echo "Recent Errors (last hour):" >> $REPORT_FILE
grep "ERROR" $LOG_FILE | tail -20 >> $REPORT_FILE

cat $REPORT_FILE
```

### 2. Performance Log Analyzer

```bash
#!/bin/bash
# scripts/analyze-performance.sh

LOG_FILE="storage/logs/laravel.log"

echo "=== Performance Analysis ==="

# Slow queries (> 1s)
echo "Slow Queries:"
grep "slow" $LOG_FILE | tail -10

# Memory issues
echo "Memory Issues:"
grep "memory" $LOG_FILE | tail -10

# Timeout errors
echo "Timeout Errors:"
grep "timeout" $LOG_FILE | tail -10
```

### 3. Security Log Analyzer

```bash
#!/bin/bash
# scripts/analyze-security.sh

LOG_FILE="storage/logs/laravel.log"

echo "=== Security Analysis ==="

# Failed login attempts
echo "Failed Logins:"
grep "login" $LOG_FILE | grep -i "fail" | tail -10

# Rate limiting hits
echo "Rate Limited Requests:"
grep "throttle" $LOG_FILE | tail -10

# Unauthorized access attempts
echo "Unauthorized Access:"
grep "403" $LOG_FILE | tail -10

# Suspicious file uploads
echo "File Upload Issues:"
grep "upload" $LOG_FILE | grep -i "error" | tail -10
```

## Laravel Telescope Integration

Enable Telescope for detailed logging:

```php
// config/telescope.php
return [
    'enabled' => env('TELESCOPE_ENABLED', false),
    
    'middleware' => [
        'web',
        \Laravel\Telescope\Http\Middleware\Authorize::class,
    ],
    
    'path' => 'telescope',
    
    'driver' => [
        'name' => 'database',
        'connection' => 'mysql',
    ],
];
```

## Custom Log Channels

### 1. Security Log

```php
// config/logging.php
'channels' => [
    'security' => [
        'driver' => 'daily',
        'path' => storage_path('logs/security.log'),
        'level' => 'info',
        'days' => 30,
    ],
],
```

### 2. Wallet Log

```php
'wallet' => [
    'driver' => 'daily',
    'path' => storage_path('logs/wallet.log'),
    'level' => 'info',
    'days' => 90,
],
```

### 3. Performance Log

```php
'performance' => [
    'driver' => 'daily',
    'path' => storage_path('logs/performance.log'),
    'level' => 'info',
    'days' => 14,
],
```

## Log Rotation

Configure log rotation:

```bash
# /etc/logrotate.d/laravel
/home/user/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 0644 www-data www-data
}
```

## Alerting Integration

### 1. Slack Notifications

```php
use Illuminate\Support\Facades\Log;

class AlertService
{
    public static function critical($message)
    {
        Log::channel('slack')->critical($message);
        
        // Also send to Slack webhook
        Http::post(config('services.slack.webhook'), [
            'text' => "🚨 CRITICAL: {$message}",
        ]);
    }
}
```

### 2. Email Alerts

```php
public static function alert($subject, $message)
{
    Mail::to(config('app.admin_email'))->send(new AlertMail($subject, $message));
}
```

## Dashboard Metrics

Track in real-time:
1. **Error Rate:** Errors per minute
2. **Response Time:** P50, P95, P99
3. **Queue Depth:** Jobs waiting
4. **Memory Usage:** Current consumption
5. **Database Connections:** Active connections

## Files to Create

1. [ ] scripts/analyze-errors.sh
2. [ ] scripts/analyze-performance.sh
3. [ ] scripts/analyze-security.sh
4. [ ] app/Services/AlertService.php
5. [ ] config/logging.php (custom channels)
6. [ ] app/Channels/SlackLogChannel.php
7. [ ] docs/monitoring-guide.md
