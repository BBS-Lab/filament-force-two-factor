<?php

declare(strict_types=1);

namespace Workbench\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Workbench\App\Models\User;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    /**
     * @var class-string<User>
     */
    protected $model = User::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            // Fresh by default, so a plain user is NOT treated as owing a rotation
            // (the panel activates the rotation plugin alongside the 2FA gate).
            'password_changed_at' => now(),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * A user already enrolled in app-based MFA.
     */
    public function withAppAuthentication(): static
    {
        return $this->state(fn (): array => [
            'app_authentication_secret' => 'JBSWY3DPEHPK3PXP',
        ]);
    }

    /**
     * A user whose password is long past the rotation window — the rotation
     * middleware would redirect them to the change screen.
     */
    public function owesRotation(): static
    {
        return $this->state(fn (): array => [
            'password_changed_at' => now()->subDays(400),
        ]);
    }
}
