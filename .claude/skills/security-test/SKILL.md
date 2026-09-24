---
name: security-test
description: Automated security tests for vulnerabilities and attack vectors
disable-model-invocation: true
---

# Security Test Suite

Comprehensive security testing for the marketplace application.

## When to Use
- After security fixes (like we just implemented)
- Before deployment (critical)
- During security audits
- When adding new features

## Test Categories

### 1. Authentication Tests

- Test unauthenticated user redirection
- Test role-based access control
- Test cannot access admin routes as buyer
- Test cannot access seller routes as buyer

### 2. Authorization Tests

- Test cannot view other users orders
- Test seller cannot edit other sellers products
- Test buyer cannot access admin functions

### 3. Input Validation Tests

- Test SQL injection prevention in search
- Test XSS prevention in reviews
- Test CSRF protection on forms
- Test malicious file upload rejection

### 4. Rate Limiting Tests

- Test login brute force protection (5 attempts per minute)
- Test API rate limiting
- Test wallet operation rate limiting

### 5. File Upload Security Tests

- Test PHP file upload rejection
- Test oversized file rejection
- Test malicious file type rejection
- Test file storage on local disk (not public)

### 6. Mass Assignment Tests

- Test cannot mass assign admin role
- Test cannot mass assign wallet balance
- Test cannot mass assign views/sales_count

### 7. Wallet Security Tests

- Test concurrent withdrawal protection
- Test negative amount rejection
- Test balance underflow prevention
- Test double-spend prevention

### 8. Session Security Tests

- Test session fixation protection
- Test session timeout
- Test secure cookie flags

## Security Checklist

### Authentication and Authorization
- [ ] Unauthenticated users redirected
- [ ] Role-based access enforced
- [ ] Users cannot access other users data
- [ ] Session fixation protection
- [ ] Password hashing verified

### Input Validation
- [ ] SQL injection prevented
- [ ] XSS attacks blocked
- [ ] CSRF protection enabled
- [ ] File upload validation
- [ ] Rate limiting active

### Data Protection
- [ ] Sensitive data not in logs
- [ ] Passwords not in responses
- [ ] API keys secured
- [ ] Database backups encrypted

### Business Logic
- [ ] Double-spend prevention
- [ ] Balance underflow protection
- [ ] Price manipulation prevention
- [ ] Status field integrity

## Running Security Tests

```bash
# Run all security tests
php artisan test --filter=Security

# Run specific category
php artisan test --filter=AuthSecurity
php artisan test --filter=WalletSecurity
php artisan test --filter=InputValidation

# Run with coverage
php artisan test --coverage --filter=Security
```

## Files to Create

1. [ ] tests/Feature/Security/AuthenticationTest.php
2. [ ] tests/Feature/Security/AuthorizationTest.php
3. [ ] tests/Feature/Security/InputValidationTest.php
4. [ ] tests/Feature/Security/FileUploadTest.php
5. [ ] tests/Feature/Security/WalletSecurityTest.php
6. [ ] tests/Feature/Security/RateLimitingTest.php
7. [ ] tests/Feature/Security/MassAssignmentTest.php
