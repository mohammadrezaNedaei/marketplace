---
name: wallet-test-suite
description: Test wallet operations for race conditions, edge cases, and financial integrity
disable-model-invocation: true
---

# Wallet Test Suite

Comprehensive tests for wallet operations ensuring financial integrity.

## When to Use
- After modifying wallet logic
- Before deployment (critical)
- When debugging balance issues
- During security audits

## Critical Test Scenarios

### 1. TOCTOU Race Condition Tests

```php
// tests/Feature/WalletRaceConditionTest.php

public function test_concurrent_deposits_cannot_double_spend()
{
    $user = User::factory()->create(['wallet_balance' => 1000]);

    // Simulate 10 concurrent deposit requests
    $concurrentRequests = [];
    for ($i = 0; $i < 10; $i++) {
        $concurrentRequests[] = async(function () use ($user) {
            return $this->actingAs($user)
                ->post('/wallet/deposit', [
                    'amount' => 100,
                    'method' => 'card',
                ]);
        });
    }

    $results = await($concurrentRequests);

    // Verify balance only increased once
    $user->refresh();
    $this->assertEquals(1100, $user->wallet_balance);
}

public function test_concurrent_withdrawals_cannot_exceed_balance()
{
    $user = User::factory()->create(['wallet_balance' => 500]);

    // Try to withdraw 300 twice concurrently
    $concurrentRequests = [
        async(fn() => $this->actingAs($user)
            ->post('/wallet/withdraw', ['amount' => 300])),
        async(fn() => $this->actingAs($user)
            ->post('/wallet/withdraw', ['amount' => 300])),
    ];

    $results = await($concurrentRequests);

    // Only one should succeed
    $user->refresh();
    $this->assertLessThanOrEqual(200, $user->wallet_balance);
}
```

### 2. Balance Integrity Tests

```php
public function test_purchase_decrements_buyer_increments_seller()
{
    $buyer = User::factory()->create(['wallet_balance' => 1000]);
    $seller = User::factory()->create(['wallet_balance' => 0]);
    $product = Product::factory()->create([
        'seller_id' => $seller->id,
        'price' => 150,
        'status' => 'active',
    ]);

    $this->actingAs($buyer)
        ->post("/orders/{$product->id}");

    $buyer->refresh();
    $seller->refresh();

    $this->assertEquals(850, $buyer->wallet_balance);
    $this->assertEquals(150, $seller->wallet_balance);
}

public function test_insufficient_balance_rejected()
{
    $user = User::factory()->create(['wallet_balance' => 100]);
    $product = Product::factory()->create(['price' => 200]);

    $response = $this->actingAs($user)
        ->post("/orders/{$product->id}");

    $response->assertRedirect();
    $this->assertEquals(100, $user->fresh()->wallet_balance);
}
```

### 3. Withdrawal Flow Tests

```php
public function test_withdrawal_creates_pending_request()
{
    $user = User::factory()->create(['wallet_balance' => 1000]);

    $response = $this->actingAs($user)
        ->post('/wallet/withdraw', [
            'amount' => 200,
            'card_number' => '6104337937691012',
        ]);

    $response->assertRedirect();
    $this->assertDatabaseHas('withdrawal_requests', [
        'user_id' => $user->id,
        'amount' => 200,
        'status' => 'pending',
    ]);
}

public function test_withdrawal_deducts_from_balance()
{
    $user = User::factory()->create(['wallet_balance' => 1000]);

    $this->actingAs($user)
        ->post('/wallet/withdraw', [
            'amount' => 200,
            'card_number' => '6104337937691012',
        ]);

    $this->assertEquals(800, $user->fresh()->wallet_balance);
}

public function test_cannot_withdraw_more_than_balance()
{
    $user = User::factory()->create(['wallet_balance' => 100]);

    $response = $this->actingAs($user)
        ->post('/wallet/withdraw', [
            'amount' => 200,
            'card_number' => '6104337937691012',
        ]);

    $this->assertEquals(100, $user->fresh()->wallet_balance);
}
```

### 4. Refund Tests

```php
public function test_order_cancellation_refunds_buyer()
{
    $buyer = User::factory()->create(['wallet_balance' => 850]);
    $seller = User::factory()->create(['wallet_balance' => 150]);
    $order = Order::factory()->create([
        'user_id' => $buyer->id,
        'product_id' => $product->id,
        'amount' => 150,
        'status' => 'paid',
    ]);

    $this->actingAs($admin)
        ->put("/admin/orders/{$order->id}/status", [
            'status' => 'canceled',
        ]);

    $buyer->refresh();
    $seller->refresh();

    $this->assertEquals(1000, $buyer->wallet_balance);
    $this->assertEquals(0, $seller->wallet_balance);
}
```

### 5. Transaction History Tests

```php
public function test_wallet_transactions_recorded_correctly()
{
    $user = User::factory()->create(['wallet_balance' => 1000]);

    $this->actingAs($user)
        ->post('/wallet/deposit', [
            'amount' => 500,
            'method' => 'card',
        ]);

    $this->assertDatabaseHas('wallet_transactions', [
        'user_id' => $user->id,
        'type' => 'deposit',
        'amount' => 500,
    ]);
}
```

## Test Coverage Checklist

### Critical (Must Pass)
- [ ] Concurrent deposit protection
- [ ] Concurrent withdrawal protection
- [ ] Double-spend prevention
- [ ] Balance underflow prevention
- [ ] Purchase decrements buyer correctly
- [ ] Purchase increments seller correctly
- [ ] Withdrawal creates pending request
- [ ] Refund on cancellation works

### Important (Should Pass)
- [ ] Transaction history recorded
- [ ] Wallet balance displayed correctly
- [ ] Card transfer creates request
- [ ] Admin approval works
- [ ] Admin rejection works

### Nice to Have
- [ ] Concurrent card transfers
- [ ] Multiple currency support (if needed)
- [ ] Transaction export functionality

## Running Tests

```bash
# Run all wallet tests
php artisan test --filter=Wallet

# Run race condition tests only
php artisan test --filter=WalletRaceCondition

# Run with coverage
php artisan test --coverage --filter=Wallet

# Run in parallel (faster)
php artisan test --parallel --filter=Wallet
```

## Files to Create

1. [ ] `tests/Feature/WalletDepositTest.php`
2. [ ] `tests/Feature/WalletWithdrawTest.php`
3. [ ] `tests/Feature/WalletRaceConditionTest.php`
4. [ ] `tests/Feature/OrderPurchaseTest.php`
5. [ ] `tests/Feature/RefundTest.php`
6. [ ] `tests/Unit/WalletBalanceTest.php`
