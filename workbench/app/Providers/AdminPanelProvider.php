<?php

declare(strict_types=1);

namespace Workbench\App\Providers;

use BBSLab\FilamentForceTwoFactor\FilamentForceTwoFactorPlugin;
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
            ->pages([Dashboard::class])
            ->multiFactorAuthentication([
                AppAuthentication::make(),
            ], isRequired: true)
            ->plugin(FilamentForceTwoFactorPlugin::make());
    }
}
