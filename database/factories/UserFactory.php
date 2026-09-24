<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'username' => fake()->unique()->userName(),
            'phone' => fake()->unique()->numerify('09#########'),
            'password' => static::$password ??= Hash::make('password'),
            'role' => 'buyer',
            'status' => 'active',
        ];
    }

    /**
     * Set as admin user.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Set as seller user.
     */
    public function seller(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'seller',
        ]);
    }

    /**
     * Set as buyer user.
     */
    public function buyer(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'buyer',
        ]);
    }

    /**
     * Set as inactive user.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }
}
