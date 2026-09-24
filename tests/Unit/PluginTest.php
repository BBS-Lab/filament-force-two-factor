<?php

declare(strict_types=1);

use BBSLab\FilamentForceTwoFactor\FilamentForceTwoFactorPlugin;
use BBSLab\FilamentForceTwoFactor\Http\Middleware\EnsureMultiFactorAuthenticationIsEnabled;
use Filament\Facades\Filament;

it('has a stable plugin id', function () {
    expect(FilamentForceTwoFactorPlugin::make()->getId())->toBe('filament-force-two-factor');
});

it('swaps the panel mandatory-MFA gate for the bypass-aware middleware', function () {
    $panel = Filament::getPanel('admin');

    expect($panel->getMultiFactorAuthenticationRequiredMiddlewareName())
        ->toBe(EnsureMultiFactorAuthenticationIsEnabled::class);
});
