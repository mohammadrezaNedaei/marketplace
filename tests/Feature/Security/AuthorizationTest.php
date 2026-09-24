<?php

namespace Tests\Feature\Security;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test seller cannot edit another seller's product.
     */
    public function test_seller_cannot_edit_other_sellers_product(): void
    {
        $seller1 = User::factory()->seller()->create();
        $seller2 = User::factory()->seller()->create();
        $product = Product::factory()->create([
            'seller_id' => $seller2->id,
        ]);

        $response = $this->actingAs($seller1)->get("/seller/products/{$product->id}/edit");

        $response->assertForbidden();
    }

    /**
     * Test buyer cannot create a product.
     */
    public function test_buyer_cannot_create_product(): void
    {
        $buyer = User::factory()->buyer()->create();

        $response = $this->actingAs($buyer)->get('/seller/products/create');

        $response->assertForbidden();
    }

    /**
     * Test admin can access all admin routes.
     */
    public function test_admin_can_access_admin_users(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/users');

        $response->assertOK();
    }

    /**
     * Test admin can access admin products management.
     */
    public function test_admin_can_access_admin_products(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/products');

        $response->assertOK();
    }

    /**
     * Test admin can access admin orders management.
     */
    public function test_admin_can_access_admin_orders(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)->get('/admin/orders');

        $response->assertOK();
    }

    /**
     * Test buyer cannot access seller dashboard.
     */
    public function test_buyer_cannot_access_seller_dashboard(): void
    {
        $buyer = User::factory()->buyer()->create();

        $response = $this->actingAs($buyer)->get('/seller/dashboard');

        $response->assertForbidden();
    }

    /**
     * Test seller cannot access buyer dashboard.
     */
    public function test_seller_cannot_access_buyer_dashboard(): void
    {
        $seller = User::factory()->seller()->create();

        $response = $this->actingAs($seller)->get('/buyer/dashboard');

        $response->assertForbidden();
    }

    /**
     * Test unauthenticated user cannot access wallet.
     */
    public function test_unauthenticated_cannot_access_wallet(): void
    {
        $response = $this->get('/wallet');

        $response->assertRedirect('/login');
    }

    /**
     * Test unauthenticated user cannot access seller dashboard.
     */
    public function test_unauthenticated_cannot_access_seller_dashboard(): void
    {
        $response = $this->get('/seller/dashboard');

        $response->assertRedirect('/login');
    }

    /**
     * Test buyer can access their own profile.
     */
    public function test_buyer_can_access_own_profile(): void
    {
        $buyer = User::factory()->buyer()->create();

        $response = $this->actingAs($buyer)->get('/profile');

        $response->assertOK();
    }

    /**
     * Test buyer cannot access admin tickets.
     */
    public function test_buyer_cannot_access_admin_tickets(): void
    {
        $buyer = User::factory()->buyer()->create();

        $response = $this->actingAs($buyer)->get('/admin/tickets');

        $response->assertForbidden();
    }

    /**
     * Test buyer cannot access admin withdrawals.
     */
    public function test_buyer_cannot_access_admin_withdrawals(): void
    {
        $buyer = User::factory()->buyer()->create();

        $response = $this->actingAs($buyer)->get('/admin/withdrawals');

        $response->assertForbidden();
    }

    /**
     * Test seller can access seller analytics.
     */
    public function test_seller_can_access_seller_analytics(): void
    {
        $seller = User::factory()->seller()->create();

        $response = $this->actingAs($seller)->get('/seller/analytics');

        $response->assertOK();
    }

    /**
     * Test buyer can access explore page.
     */
    public function test_buyer_can_access_explore(): void
    {
        $buyer = User::factory()->buyer()->create();

        $response = $this->actingAs($buyer)->get('/explore');

        $response->assertOK();
    }

    /**
     * Test guest can access explore page.
     */
    public function test_guest_can_access_explore(): void
    {
        $response = $this->get('/explore');

        $response->assertOK();
    }
}
