<?php

declare(strict_types=1);

use BBSLab\FilamentPasswordRotation\Filament\Pages\ForcePasswordChange;
use BBSLab\LaravelOkta\Http\Controllers\OktaController;
use Workbench\App\Models\User;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;

// End-to-end interop with the REAL sibling packages installed and both gates
// (2FA + rotation) active on the panel — the actual objective, not a hand-made
// bypass closure.

it('gives rotation precedence over 2FA: an un-enrolled user owing a rotation lands on the rotation page, not the 2FA set-up', function () {
    // Un-enrolled (no MFA) AND owes a rotation. laravel-password-rotation's
    // owes-rotation bypass makes the 2FA gate yield, so the user is sent straight
    // to the rotation screen — no set-up <-> rotate loop.
    actingAs(User::factory()->owesRotation()->create());

    get(route('filament.admin.pages.dashboard'))
        ->assertRedirect(ForcePasswordChange::getUrl(panel: 'admin'));
});

it('lets an Okta-authenticated user bypass BOTH the 2FA gate and the rotation gate', function () {
    // Un-enrolled AND owes a rotation — but authenticated via Okta, so laravel-okta's
    // registrations exempt the session from BOTH registries and the user reaches
    // the panel. Proves "2FA except Okta" and "rotation except Okta" end to end.
    actingAs(User::factory()->owesRotation()->create());

    test()
        ->withSession([OktaController::AUTHENTICATED_SESSION_KEY => true])
        ->get(route('filament.admin.pages.dashboard'))
        ->assertSuccessful();
});
