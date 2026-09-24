<?php

declare(strict_types=1);

namespace BBSLab\FilamentForceTwoFactor\Tests;

use BBSLab\FilamentForceTwoFactor\FilamentForceTwoFactorServiceProvider;
use BBSLab\FilamentPasswordRotation\FilamentPasswordRotationServiceProvider;
use BBSLab\LaravelForceTwoFactor\LaravelForceTwoFactorServiceProvider;
use BBSLab\LaravelOkta\LaravelOktaServiceProvider;
use BBSLab\LaravelPasswordRotation\LaravelPasswordRotationServiceProvider;
use BladeUI\Heroicons\BladeHeroiconsServiceProvider;
use BladeUI\Icons\BladeIconsServiceProvider;
use Filament\Actions\ActionsServiceProvider;
use Filament\FilamentServiceProvider;
use Filament\Forms\FormsServiceProvider;
use Filament\Infolists\InfolistsServiceProvider;
use Filament\Notifications\NotificationsServiceProvider;
use Filament\QueryBuilder\QueryBuilderServiceProvider;
use Filament\Schemas\SchemasServiceProvider;
use Filament\Support\SupportServiceProvider;
use Filament\Tables\TablesServiceProvider;
use Filament\Widgets\WidgetsServiceProvider;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Orchestra;
use SocialiteProviders\Manager\ServiceProvider as SocialiteManagerServiceProvider;
use Workbench\App\Models\User;
use Workbench\App\Providers\AdminPanelProvider;

abstract class TestCase extends Orchestra
{
    use LazilyRefreshDatabase;
    use WithWorkbench;

    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            // Filament's stack (auto-discovered in a real app; listed explicitly in
            // the isolated harness so the `filament` container binding exists).
            BladeIconsServiceProvider::class,
            BladeHeroiconsServiceProvider::class,
            LivewireServiceProvider::class,
            SupportServiceProvider::class,
            ActionsServiceProvider::class,
            FormsServiceProvider::class,
            InfolistsServiceProvider::class,
            NotificationsServiceProvider::class,
            SchemasServiceProvider::class,
            TablesServiceProvider::class,
            WidgetsServiceProvider::class,
            QueryBuilderServiceProvider::class,
            FilamentServiceProvider::class,
            // socialiteproviders/manager is deferred — register it so laravel-okta
            // (below) can extend Socialite during boot without error.
            SocialiteManagerServiceProvider::class,
            // The framework-agnostic base binds the shared TwoFactorManager bypass
            // registry the gate reads from. The real sibling packages are booted too
            // so the interop test exercises their ACTUAL registry registrations:
            // laravel-password-rotation registers owes-rotation → 2FA bypass;
            // laravel-okta registers okta_authenticated → both registries.
            LaravelForceTwoFactorServiceProvider::class,
            LaravelPasswordRotationServiceProvider::class,
            FilamentPasswordRotationServiceProvider::class,
            LaravelOktaServiceProvider::class,
            FilamentForceTwoFactorServiceProvider::class,
            // The workbench panel: requires app-based MFA and activates both plugins.
            AdminPanelProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('auth.providers.users.model', User::class);
    }
}
