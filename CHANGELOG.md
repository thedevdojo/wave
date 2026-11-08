# Changelog

Notable changes to Wave. The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/). Older releases are listed on [GitHub Releases](https://github.com/thedevdojo/wave/releases).

## [Unreleased]

## [3.3.0] - 2026-11-08

### Upgrade notes

- With email verification turned on, users who haven't verified are now sent to /auth/verify. Registering never sets `email_verified_at` while verification is off, so if you turn it on for an existing app, all existing users have to verify first ([#410])
- Tests run on in-memory SQLite with `RefreshDatabase` and only roles and the Anchor theme are seeded. Your own tests that relied on seeded data (`admin@admin.com`, plans, pages) need to create it with factories ([#411])
- `tailwind.config.js`, `resources/css/filament/admin/tailwind.config.js` and `postcss.config.js` were removed since the Vite build never used them. If you customized them, move those changes into your CSS with `@theme` / `@plugin` ([#413], [#415])

### Added

- `email_verification_codes` migrations from devdojo/auth, so registering with email verification on no longer fails ([#410])
- A two-factor authentication section on /settings/security when `enable_2fa` is on ([#410])
- `wave.plugins_path` config value, defaults to `resource_path('plugins')`. Older `config/wave.php` files without it keep working ([#411])
- CI runs the tests on PHP 8.3, 8.4 and 8.5 with `composer audit`, Pest and Pint run nightly, and a new Frontend workflow runs `npm audit` and `npm run build` ([#409])
- `CHANGELOG.md`, and a release workflow that fails when `wave/wave.json` doesn't match the release tag ([#413])

### Changed

- With email verification on, theme pages, the authenticated Wave routes and Livewire updates require a verified email ([#410])
- With email verification on, `/api/register` sends the verification email and returns 201 with a message instead of a token, and `/api/login` returns 403 for unverified users ([#410])
- The seeded demo admin is marked as verified ([#410])
- The test suite no longer touches `database/database.sqlite` or `resources/plugins`, and passes with `pest --parallel` ([#411])
- Draft and pending posts now return 404 for non-admins on every theme, not just Anchor ([#414])
- Guests hitting subscriber-only routes are sent to login and back, instead of to the billing page ([#414])
- The browser installer shows again when the database has no users yet ([#414])
- Dropped the unused `@tailwindcss/postcss` and `postcss` dependencies ([#415])

### Fixed

- `Subscription` casts `trial_ends_at` and `ends_at` to datetime ([#297])
- The Plugins and Themes admin pages no longer fail with `open_basedir` set, and loading plugins no longer writes info logs on every request ([#412])
- The demo theme switcher and the redirect to the installer when there's no database connection never ran, their middleware is now registered through the HTTP kernel ([#416])
- Dark mode styles on the account deletion settings page ([#414])
- `ThemesServiceProvider` was registered twice ([#413])

## [3.2.0] - 2026-11-08

### Upgrade notes

- `ActivityLog` moved from `App\Models\ActivityLog` to `Wave\ActivityLog`, update any imports ([#344])
- `config/settings.php` was removed, the Stripe publishable key is now `config('wave.stripe.publishable_key')` ([#349])
- The minimum password length went from 5 to 8 ([#349])
- `/api/token` returns 422 when `key` is missing (was 401) and 401 for an invalid key (was 400). The response also includes `token_type` and `expires_in` ([#396])
- `wave.api.key_token_expires` is now applied and defaults to 60 minutes. Login, register and refresh tokens use `wave.api.auth_token_expires` instead of `JWT_TTL` ([#396])
- `.env.example` ships an empty `JWT_SECRET`. If you're still on the old `SeCrEtKeY` placeholder, run `php artisan jwt:secret` ([#396])
- `plugin:create` derives the class name from the folder name, so "SEO Tools" now generates `SeoToolsPlugin`. Plugins generated before this need `: void` added to `register()` and `boot()` ([#395])
- Draft and pending posts and inactive pages are no longer public, and `Page::link()` points at `/{slug}` ([#394])

The full list of changes is in the [3.2.0 release notes](https://github.com/thedevdojo/wave/releases/tag/3.2.0).

[Unreleased]: https://github.com/thedevdojo/wave/compare/3.3.0...HEAD
[3.3.0]: https://github.com/thedevdojo/wave/compare/3.2.0...3.3.0
[3.2.0]: https://github.com/thedevdojo/wave/compare/3.1.5...3.2.0
[#297]: https://github.com/thedevdojo/wave/pull/297
[#344]: https://github.com/thedevdojo/wave/pull/344
[#349]: https://github.com/thedevdojo/wave/pull/349
[#394]: https://github.com/thedevdojo/wave/pull/394
[#395]: https://github.com/thedevdojo/wave/pull/395
[#396]: https://github.com/thedevdojo/wave/pull/396
[#409]: https://github.com/thedevdojo/wave/pull/409
[#410]: https://github.com/thedevdojo/wave/pull/410
[#411]: https://github.com/thedevdojo/wave/pull/411
[#412]: https://github.com/thedevdojo/wave/pull/412
[#413]: https://github.com/thedevdojo/wave/pull/413
[#414]: https://github.com/thedevdojo/wave/pull/414
[#415]: https://github.com/thedevdojo/wave/pull/415
[#416]: https://github.com/thedevdojo/wave/pull/416
