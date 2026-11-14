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
     * NOTE: the `users` table in this project has no `email_verified_at`
     * or `remember_token` columns, so neither is produced here.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'role' => User::ROLE_ADMIN,
        ];
    }

    /**
     * Indicate that the user is a super administrator.
     */
    public function superadmin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => User::ROLE_SUPERADMIN,
        ]);
    }
}
