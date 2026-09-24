<?php

declare(strict_types=1);

namespace BBSLab\FilamentForceTwoFactor;

use BBSLab\FilamentForceTwoFactor\Http\Middleware\EnsureMultiFactorAuthenticationIsEnabled;
use Filament\Contracts\Plugin;
use Filament\Panel;

/**
 * Swaps the panel's mandatory multi-factor gate for the bypass-aware
 * {@see EnsureMultiFactorAuthenticationIsEnabled}. Add it to a panel that already
 * requires MFA:
 *
 *     $panel
 *         ->multiFactorAuthentication([AppAuthentication::make()], isRequired: true)
 *         ->plugin(FilamentForceTwoFactorPlugin::make());
 *
 * The gate then honours every reason registered in the shared
 * bbs-lab/laravel-force-two-factor bypass registry (Okta sessions, a pending
 * password rotation, host callbacks) before Filament's native gate runs.
 */
class FilamentForceTwoFactorPlugin implements Plugin
{
    public static function make(): static
    {
        return app(static::class);
    }

    public function getId(): string
    {
        return 'filament-force-two-factor';
    }

    public function register(Panel $panel): void
    {
        $panel->multiFactorAuthenticationRequiredMiddlewareName(EnsureMultiFactorAuthenticationIsEnabled::class);
    }

    public function boot(Panel $panel): void
    {
        //
    }
}
