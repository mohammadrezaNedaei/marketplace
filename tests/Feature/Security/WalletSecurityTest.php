<?php

namespace Tests\Feature\Security;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WalletSecurityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test buyer cannot purchase with insufficient balance.
     */
    public function test_buyer_cannot_purchase_with_insufficient_balance(): void
    {
        $seller = User::factory()->seller()->create();
        $product = Product::factory()->create([
            'seller_id' => $seller->id,
            'price' => 50000,
            'status' => 'active',
        ]);
        $buyer = User::factory()->buyer()->create([
            'wallet_balance' => 1000, // Less than product price
        ]);

        $response = $this->actingAs($buyer)->post("/orders/{$product->id}");

        $response->assertSessionHas('error');
        $this->assertDatabaseMissing('orders', [
            'user_id' => $buyer->id,
            'product_id' => $product->id,
        ]);
    }

    /**
     * Test wallet balance is decremented on successful purchase.
     */
    public function test_wallet_balance_decremented_on_purchase(): void
    {
        $seller = User::factory()->seller()->create([
            'wallet_balance' => 0,
        ]);
        $product = Product::factory()->create([
            'seller_id' => $seller->id,
            'price' => 30000,
            'status' => 'active',
        ]);
        $buyer = User::factory()->buyer()->create([
            'wallet_balance' => 100000,
        ]);

        $this->actingAs($buyer)->post("/orders/{$product->id}");

        $buyer->refresh();
        $this->assertEquals(70000, $buyer->wallet_balance);

        $this->assertDatabaseHas('wallet_transactions', [
            'user_id' => $buyer->id,
            'type' => 'purchase',
            'amount' => 30000,
        ]);
    }

    /**
     * Test buyer cannot purchase same product twice.
     */
    public function test_buyer_cannot_purchase_same_product_twice(): void
    {
        $seller = User::factory()->seller()->create();
        $product = Product::factory()->create([
            'seller_id' => $seller->id,
            'price' => 25000,
            'status' => 'active',
        ]);
        $buyer = User::factory()->buyer()->create([
            'wallet_balance' => 200000,
        ]);

        // First purchase succeeds
        $this->actingAs($buyer)->post("/orders/{$product->id}");
        $buyer->refresh();
        $balanceAfterFirst = $buyer->wallet_balance;

        // Second purchase should fail
        $response = $this->actingAs($buyer)->post("/orders/{$product->id}");

        $response->assertSessionHas('error');
        $buyer->refresh();
        $this->assertEquals($balanceAfterFirst, $buyer->wallet_balance);
    }

    /**
     * Test withdrawal request creates pending transaction.
     */
    public function test_withdrawal_request_creates_pending_transaction(): void
    {
        $seller = User::factory()->seller()->create([
            'wallet_balance' => 100000,
        ]);

        $response = $this->actingAs($seller)->post('/wallet/withdraw', [
            'amount' => 30000,
            'card_number' => '6037991234567890',
        ]);

        $response->assertSessionHas('success');

        $this->assertDatabaseHas('withdrawal_requests', [
            'user_id' => $seller->id,
            'amount' => 30000,
            'status' => 'pending',
        ]);

        // Balance should not be changed yet (awaiting admin approval)
        $seller->refresh();
        $this->assertEquals(100000, $seller->wallet_balance);
    }

    /**
     * Test user cannot withdraw more than balance.
     */
    public function test_user_cannot_withdraw_more_than_balance(): void
    {
        $seller = User::factory()->seller()->create([
            'wallet_balance' => 5000,
        ]);

        $response = $this->actingAs($seller)->post('/wallet/withdraw', [
            'amount' => 50000,
            'card_number' => '6037991234567890',
        ]);

        $response->assertSessionHasErrors();
        $this->assertDatabaseMissing('withdrawal_requests', [
            'user_id' => $seller->id,
            'amount' => 50000,
        ]);
    }

    /**
     * Test wallet transaction amount cannot be zero or negative via deposit.
     */
    public function test_deposit_rejects_zero_amount(): void
    {
        $buyer = User::factory()->buyer()->create([
            'wallet_balance' => 0,
        ]);

        $response = $this->actingAs($buyer)->post('/wallet/deposit', [
            'amount' => 0,
        ]);

        $response->assertSessionHasErrors();
    }

    /**
     * Test concurrent purchase attempts cannot double-spend.
     */
    public function test_concurrent_purchases_prevent_double_spend(): void
    {
        $seller = User::factory()->seller()->create();
        $product = Product::factory()->create([
            'seller_id' => $seller->id,
            'price' => 50000,
            'status' => 'active',
        ]);
        $buyer = User::factory()->buyer()->create([
            'wallet_balance' => 75000, // Enough for only one purchase
        ]);

        // Simulate concurrent purchase attempts
        $pending = [];
        for ($i = 0; $i < 3; $i++) {
            $pending[] = $this->actingAs($buyer)->post("/orders/{$product->id}");
        }

        // Only one order should exist
        $orderCount = Order::where('user_id', $buyer->id)
            ->where('product_id', $product->id)
            ->where('status', 'paid')
            ->count();

        $this->assertLessThanOrEqual(1, $orderCount);

        // Balance should only be decremented once
        $buyer->refresh();
        $this->assertGreaterThanOrEqual(25000, $buyer->wallet_balance);
    }
}
