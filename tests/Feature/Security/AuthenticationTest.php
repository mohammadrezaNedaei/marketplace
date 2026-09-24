<?php

namespace Tests\Feature\Security;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test unauthenticated user is redirected from protected routes.
     */
    public function test_unauthenticated_user_redirected_from_wallet(): void
    {
        $response = $this->get('/wallet');

        $response->assertRedirect('/login');
    }

    /**
     * Test unauthenticated user is redirected from profile.
     */
    public function test_unauthenticated_user_redirected_from_profile(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    /**
     * Test buyer cannot access admin routes.
     */
    public function test_buyer_cannot_access_admin_routes(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    /**
     * Test seller cannot access admin routes.
     */
    public function test_seller_cannot_access_admin_routes(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);

        $response = $this->actingAs($seller)->get('/admin/dashboard');

        $response->assertForbidden();
    }

    /**
     * Test buyer cannot access seller routes.
     */
    public function test_buyer_cannot_access_seller_routes(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->get('/seller/dashboard');

        $response->assertForbidden();
    }

    /**
     * Test admin can access admin routes.
     */
    public function test_admin_can_access_admin_routes(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get('/admin/dashboard');

        $response->assertOK();
    }

    /**
     * Test seller can access seller routes.
     */
    public function test_seller_can_access_seller_routes(): void
    {
        $seller = User::factory()->create(['role' => 'seller']);

        $response = $this->actingAs($seller)->get('/seller/dashboard');

        $response->assertOK();
    }

    /**
     * Test buyer can access buyer routes.
     */
    public function test_buyer_can_access_buyer_routes(): void
    {
        $buyer = User::factory()->create(['role' => 'buyer']);

        $response = $this->actingAs($buyer)->get('/buyer/dashboard');

        $response->assertOK();
    }

    /**
     * Test login throttle is active.
     */
    public function test_login_throttle_is_active(): void
    {
        // Make 5 failed login attempts
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'phone' => '09123456777',
                'password' => 'wrongpassword',
            ]);
        }

        // 6th attempt should be rate limited (429)
        $response = $this->post('/login', [
            'phone' => '09123456777',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(429);
    }

    /**
     * Test password is hashed in database.
     */
    public function test_password_is_hashed(): void
    {
        $password = 'testpassword123';
        $user = User::factory()->create([
            'password' => \Illuminate\Support\Facades\Hash::make($password),
        ]);

        // Password should not be stored in plain text
        $this->assertNotEquals($password, $user->password);

        // Should be verifiable
        $this->assertTrue(\Hash::check($password, $user->password));
    }

    /**
     * Test user cannot elevate role via registration.
     */
    public function test_user_cannot_mass_assign_role(): void
    {
        // Attempting to register with admin role should be rejected by validation
        $response = $this->post('/register', [
            'username' => 'hacker',
            'phone' => '09999999999',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'admin', // Should be rejected by validation
        ]);

        // User should NOT be created because admin role is invalid
        $this->assertDatabaseMissing('users', ['phone' => '09999999999']);
    }

    /**
     * Test user cannot mass assign wallet_balance.
     */
    public function test_user_cannot_mass_assign_wallet_balance(): void
    {
        $user = User::factory()->create(['wallet_balance' => 0]);

        $this->actingAs($user)->put('/profile', [
            'username' => 'newname',
            'wallet_balance' => 1000000, // Should be ignored
        ]);

        $this->assertEquals(0, $user->fresh()->wallet_balance);
    }

    /**
     * Test health check endpoint is accessible without auth.
     */
    public function test_health_check_is_accessible(): void
    {
        $response = $this->get('/health');

        $response->assertOK();
        $response->assertJsonStructure([
            'status',
            'timestamp',
            'checks' => [
                'database',
                'cache',
                'queue',
                'storage',
            ],
        ]);
    }

    /**
     * Test health check returns healthy status.
     */
    public function test_health_check_returns_healthy(): void
    {
        $response = $this->get('/health');

        $response->assertOK();
        $response->assertJson([
            'status' => 'healthy',
        ]);
    }
}
