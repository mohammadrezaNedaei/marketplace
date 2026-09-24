---
name: buyer-flow-validator
description: Test complete buyer journey from registration to purchase and review
disable-model-invocation: true
---

# Buyer Flow Validator

Test complete buyer journeys to ensure smooth user experience.

## When to Use
- After modifying purchase flow
- Before launching new features
- During user acceptance testing
- When debugging checkout issues

## Critical User Journeys

### 1. Registration → First Purchase

Test the complete flow:
- Register new buyer account
- Browse product listings
- View product details
- Attempt purchase (should fail without balance)
- Deposit to wallet
- Complete purchase
- Verify order created

### 2. Purchase → Download → Review

Test post-purchase flow:
- Complete purchase
- Access order details
- Download product files
- Submit review with rating
- Verify review marked as verified purchase

### 3. Multiple Purchases Flow

Test multiple transactions:
- Make 3 separate purchases
- Verify balance decremented correctly
- Verify all orders created
- Verify seller received funds

### 4. Wallet Operations Flow

Test wallet lifecycle:
- Initial deposit
- Check balance displays
- Withdrawal request
- Verify balance updated
- Check transaction history

## Validation Checkpoints

### Registration Validation

- [ ] Username uniqueness enforced
- [ ] Phone number format validated
- [ ] Password strength enforced
- [ ] Role assignment correct (default: buyer)
- [ ] Welcome notification sent

### Product Browsing Validation

- [ ] Only active products displayed
- [ ] Pagination works correctly
- [ ] Search functionality works
- [ ] Category filtering works
- [ ] Product detail loads fast (< 1s)

### Checkout Validation

- [ ] Price calculated correctly
- [ ] Balance check before purchase
- [ ] Atomic transaction (no double-spend)
- [ ] Order status set to paid
- [ ] Wallet transaction recorded
- [ ] Seller balance incremented

### Post-Purchase Validation

- [ ] Order appears in purchase history
- [ ] Download link accessible
- [ ] Review can be submitted
- [ ] Review marked as verified purchase

## Edge Cases to Test

### 1. Insufficient Balance

User with 100 balance tries to buy 200 product:
- Purchase should be rejected
- Balance should remain 100
- No order created

### 2. Already Purchased

User tries to buy same product twice:
- Second purchase rejected
- Only one order in database
- Balance only deducted once

### 3. Concurrent Purchases

Two simultaneous purchase attempts:
- Only one should succeed
- Balance protected from double-spend
- Single order created

### 4. Product Becomes Unavailable

User tries to buy inactive product:
- Purchase rejected with 404
- Balance unchanged

## Performance Benchmarks

Target metrics for buyer flow:
1. **Page Load:** Product detail < 1s
2. **Checkout:** < 2s total
3. **Download:** < 3s to start
4. **Review Submit:** < 1s

## User Experience Metrics

Track these in production:
1. **Cart Abandonment Rate:** < 30%
2. **Purchase Completion Rate:** > 70%
3. **Average Time to Purchase:** < 5 minutes
4. **Review Submission Rate:** > 20% of purchases

## Files to Create

1. [ ] tests/Feature/BuyerRegistrationTest.php
2. [ ] tests/Feature/ProductBrowsingTest.php
3. [ ] tests/Feature/CheckoutFlowTest.php
4. [ ] tests/Feature/DownloadFlowTest.php
5. [ ] tests/Feature/ReviewSubmissionTest.php
6. [ ] tests/Feature/WalletOperationsTest.php
7. [ ] docs/user-guide.md
