<?php

declare(strict_types=1);

namespace Workbench\Database\Seeders;

use Illuminate\Database\Seeder;
use Workbench\App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * A single user so the panel can be exercised with `composer serve`
     * (password is "password").
     */
    public function run(): void
    {
        User::query()->firstOrCreate(
            ['email' => 'admin@laravel.com'],
            [
                'name' => 'Filament Admin',
                'password' => 'password',
            ],
        );
    }
}
