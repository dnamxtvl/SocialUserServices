<?php

namespace Database\Factories;

use App\Data\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected $model = User::class;

    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => now()->timestamp . Str::random(20) . fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'phone_number' => fake('vi_VN')->phoneNumber(),
            'gender' => fake()->numberBetween(config('user.gender.female'), config('user.gender.other')),
            'password' => static::$password ??= Hash::make('password'),
            'relationship_status' => fake()->numberBetween(config('user.relationship_status.single'), config('user.relationship_status.live_together')),
            'latest_login' => fake()->dateTimeBetween(now()->subYear(), now()),
            'latest_ip_login' => fake()->ipv4(),
            'day_of_birth' => fake()->dayOfMonth(),
            'last_activity_at' => fake()->dateTimeBetween(now()->subYear(), now()),
            'status_active' => fake()->numberBetween(config('user.status_active.online'), config('user.status_active.offline')),
            'month_of_birth' => fake()->numberBetween(config('validation.month.min_value'), config('validation.month.max_value')),
            'year_of_birth' => fake()->numberBetween(config('validation.year.min_value'), now()->subYear()->year),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
