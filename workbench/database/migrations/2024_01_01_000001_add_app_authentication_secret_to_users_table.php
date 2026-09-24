<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            // Filament's app-based MFA provider stores the TOTP secret here; a null
            // value means "not enrolled", which is what the forced-2FA gate acts on.
            $table->text('app_authentication_secret')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn('app_authentication_secret');
        });
    }
};
