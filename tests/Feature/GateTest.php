<?php

declare(strict_types=1);

use BBSLab\LaravelForceTwoFactor\Facades\ForceTwoFactor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Workbench\App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

function dashboardUrl(): string
{
    return route('filament.admin.pages.dashboard');
}

it('forces MFA enrolment by redirecting an un-enrolled user away from a panel page', function () {
    actingAs(User::factory()->create());

    get(dashboardUrl())->assertRedirect();
});

it('lets an enrolled user reach the panel', function () {
    actingAs(User::factory()->withAppAuthentication()->create());

    get(dashboardUrl())->assertSuccessful();
});

it('lets a bypassed user through the gate without enrolling', function () {
    ForceTwoFactor::bypass(fn (Request $r, Authenticatable $u): bool => true);
    actingAs(User::factory()->create());

    get(dashboardUrl())->assertSuccessful();
});

it('does not force MFA when the shared master switch is off', function () {
    config(['laravel-force-two-factor.enabled' => false]);
    actingAs(User::factory()->create());

    get(dashboardUrl())->assertSuccessful();
});
