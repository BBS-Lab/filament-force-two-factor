<?php

declare(strict_types=1);

namespace BBSLab\FilamentForceTwoFactor;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class FilamentForceTwoFactorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        // No config of its own: the master switch and the bypass registry live in
        // the base bbs-lab/laravel-force-two-factor package.
        $package->name('filament-force-two-factor');
    }
}
