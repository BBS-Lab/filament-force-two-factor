<?php

declare(strict_types=1);

arch('no debugging helpers are left behind')
    ->expect(['dd', 'dump', 'ray', 'var_dump', 'dexit'])
    ->not->toBeUsed();

arch('the whole package declares strict types')
    ->expect('BBSLab\FilamentForceTwoFactor')
    ->toUseStrictTypes();

arch('the gate extends Filament\'s native middleware')
    ->expect('BBSLab\FilamentForceTwoFactor\Http\Middleware\EnsureMultiFactorAuthenticationIsEnabled')
    ->toExtend('Filament\Auth\MultiFactor\Http\Middleware\EnsureMultiFactorAuthenticationIsEnabled');

arch('the plugin implements Filament\'s plugin contract')
    ->expect('BBSLab\FilamentForceTwoFactor\FilamentForceTwoFactorPlugin')
    ->toImplement('Filament\Contracts\Plugin');
