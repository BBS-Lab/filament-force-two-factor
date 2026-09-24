<?php

declare(strict_types=1);

namespace BBSLab\FilamentForceTwoFactor\Http\Middleware;

use BBSLab\FilamentForceTwoFactor\FilamentForceTwoFactorPlugin;
use BBSLab\LaravelForceTwoFactor\TwoFactorManager;
use Closure;
use Filament\Auth\MultiFactor\Http\Middleware\EnsureMultiFactorAuthenticationIsEnabled as BaseEnsureMultiFactorAuthenticationIsEnabled;
use Filament\Facades\Filament;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The mandatory multi-factor gate for a Filament panel, made bypass-aware.
 *
 * Filament's native gate ({@see BaseEnsureMultiFactorAuthenticationIsEnabled})
 * redirects every user without MFA to the set-up page. This variant first
 * consults the shared bbs-lab/laravel-force-two-factor registry, so any reason to
 * skip 2FA — an Okta session, a user who still owes a forced password rotation,
 * or a host-registered callback — lets the request through before the native gate
 * runs. Wire it via the panel's multiFactorAuthenticationRequiredMiddlewareName()
 * (see {@see FilamentForceTwoFactorPlugin}).
 */
class EnsureMultiFactorAuthenticationIsEnabled extends BaseEnsureMultiFactorAuthenticationIsEnabled
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): mixed
    {
        // The shared master switch (base package): when off, never force MFA.
        if (! config('laravel-force-two-factor.enabled')) {
            return $next($request);
        }

        $user = Filament::auth()->user();

        // A registered reason to skip 2FA (Okta, pending rotation, host callback)
        // lets the request through before the native gate would redirect to set-up.
        if ($user instanceof Authenticatable
            && app(TwoFactorManager::class)->shouldBypass($request, $user)
        ) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
