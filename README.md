# Force Two-Factor for Filament

[![Tests](https://github.com/BBS-Lab/filament-force-two-factor/actions/workflows/run-tests.yml/badge.svg?branch=main)](https://github.com/BBS-Lab/filament-force-two-factor/actions/workflows/run-tests.yml)
[![PHPStan](https://github.com/BBS-Lab/filament-force-two-factor/actions/workflows/phpstan.yml/badge.svg?branch=main)](https://github.com/BBS-Lab/filament-force-two-factor/actions/workflows/phpstan.yml)

The Filament adapter for [`bbs-lab/laravel-force-two-factor`](https://github.com/BBS-Lab/laravel-force-two-factor). Filament already ships mandatory multi-factor authentication; this package makes that gate **bypass-aware**, so specific users skip the enrolment redirect — e.g. **Okta** users (their second factor lives at the identity provider) and users who still owe a **forced password rotation** (they must change their password first).

```bash
composer require bbs-lab/filament-force-two-factor
```

## Usage

Enable Filament's required MFA on your panel as usual, then add the plugin — it swaps the panel's mandatory-MFA gate for the bypass-aware one:

```php
use BBSLab\FilamentForceTwoFactor\FilamentForceTwoFactorPlugin;
use Filament\Auth\MultiFactor\App\AppAuthentication;

public function panel(Panel $panel): Panel
{
    return $panel
        ->multiFactorAuthentication([
            AppAuthentication::make(),
        ], isRequired: true)
        ->plugin(FilamentForceTwoFactorPlugin::make());
}
```

That's it. The gate now honours every reason registered in the shared bypass registry.

## Bypasses compose automatically

A panel can wire only one mandatory-MFA gate, but several packages have a legitimate reason to let a user skip it. Each registers a callback in the shared [`bbs-lab/laravel-force-two-factor`](https://github.com/BBS-Lab/laravel-force-two-factor) registry; the gate bypasses as soon as **any** returns `true`:

- **[`bbs-lab/laravel-okta`](https://github.com/BBS-Lab/laravel-okta)** — skips forced 2FA for users authenticated via Okta.
- **[`bbs-lab/laravel-password-rotation`](https://github.com/BBS-Lab/laravel-password-rotation)** — skips forced 2FA while a user still owes a password rotation, so **rotation runs before 2FA** (no set-up ⇄ rotate redirect loop).

You can register your own reason too:

```php
use BBSLab\LaravelForceTwoFactor\Facades\ForceTwoFactor;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;

ForceTwoFactor::bypass(fn (Request $r, Authenticatable $u): bool => /* ... */);
```

The same registry is read by [`bbs-lab/nova-force-two-factor`](https://github.com/BBS-Lab/nova-force-two-factor), so a reason registered once applies to whichever panel enforces 2FA.

## Configuration

The master switch lives in the base package (`config/laravel-force-two-factor.php`, env `FORCE_TWO_FACTOR_ENABLED`). When it is off, the gate never forces enrolment.

## Testing

```bash
composer test
```

## License

MIT. See [LICENSE.md](LICENSE.md).
