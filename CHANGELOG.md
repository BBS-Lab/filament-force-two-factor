# Changelog

All notable changes to `bbs-lab/filament-force-two-factor` will be documented in this file.

## v1.0.0 - 2026-09-24

Filament adapter for [bbs-lab/laravel-force-two-factor](https://github.com/BBS-Lab/laravel-force-two-factor) — a bypass-aware mandatory multi-factor gate.

### ✨ Features

- `EnsureMultiFactorAuthenticationIsEnabled`: extends Filament's native mandatory-MFA middleware and, before it redirects an un-enrolled user to the set-up page, consults the shared `bbs-lab/laravel-force-two-factor` bypass registry — so Okta users, users owing a password rotation, or any host-registered reason skip enrolment.
- `FilamentForceTwoFactorPlugin`: wires the gate onto a panel via `multiFactorAuthenticationRequiredMiddlewareName()`; add it after `multiFactorAuthentication([...], isRequired: true)`.
- Honours the shared master switch `config('laravel-force-two-factor.enabled')`.

### 📦 Requirements

- PHP 8.2+, Laravel 11/12/13, Filament 5.
