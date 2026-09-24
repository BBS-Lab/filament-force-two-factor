<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use BBSLab\LaravelPasswordRotation\Concerns\RotatesPassword;
use BBSLab\LaravelPasswordRotation\Contracts\MustRotatePassword;
use Filament\Auth\MultiFactor\App\Contracts\HasAppAuthentication;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use SensitiveParameter;
use Workbench\Database\Factories\UserFactory;

/**
 * Workbench user for the Filament panel. Implements HasAppAuthentication so
 * Filament's app-based MFA provider can read enrolment state: a null
 * `app_authentication_secret` means "not enrolled", which is what the gate acts
 * on. canAccessPanel is permissive — the tests exercise the 2FA gate, not
 * panel authorization.
 */
class User extends Authenticatable implements FilamentUser, HasAppAuthentication, MustRotatePassword
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, RotatesPassword;

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'app_authentication_secret',
        'password_changed_at',
    ];

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'app_authentication_secret',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'password_changed_at' => 'datetime',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getAppAuthenticationSecret(): ?string
    {
        return $this->app_authentication_secret;
    }

    public function saveAppAuthenticationSecret(#[SensitiveParameter] ?string $secret): void
    {
        $this->app_authentication_secret = $secret;
        $this->save();
    }

    public function getAppAuthenticationHolderName(): string
    {
        return $this->email;
    }
}
