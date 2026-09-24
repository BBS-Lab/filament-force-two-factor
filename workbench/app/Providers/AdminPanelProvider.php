<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use BBSLab\FilamentForceTwoFactor\FilamentForceTwoFactorPlugin;
use BBSLab\FilamentPasswordRotation\FilamentPasswordRotationPlugin;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;

/**
 * A minimal Filament panel that requires app-based MFA and swaps in the
 * bypass-aware gate through the plugin, for exercising the package end to end.
 */
class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->authGuard('web')
            // The web group brings StartSession, so the Okta bypass (which reads
            // session('okta_authenticated')) works — as it does in a real app.
            ->middleware(['web'])
            ->pages([Dashboard::class])
            ->multiFactorAuthentication([
                AppAuthentication::make(),
            ], isRequired: true)
            ->plugin(FilamentForceTwoFactorPlugin::make())
            // The rotation gate too, so the interop test proves rotation-before-2FA.
            ->plugin(FilamentPasswordRotationPlugin::make());
    }
}
