# Changelog

Notable changes to Wave. The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/). Older releases are listed on [GitHub Releases](https://github.com/thedevdojo/wave/releases).

## [Unreleased]

## [3.6.1] - 2026-11-09

### Removed

- The unused Wave 2 billing routes (`cancel`, `subscribe`, `switch-plans`, `checkout/welcome`) and their controller methods and partials ([#438])

### Fixed

- Changing a user's roles, in the admin or with `assignRole`/`removeRole`, clears their cached admin and subscriber status right away ([#439])
- `/install` explains how to install from the command line when the app isn't running locally, instead of showing an empty page, and redirects home once Wave is installed ([#440])
- The Paddle invoice link returns a 404 instead of a 500 when Paddle has no invoice for the transaction ([#441])

## [3.6.0] - 2026-11-09

### Added

- Assign permissions to roles in the admin, from the role form or the permission form. The roles table shows how many permissions each role has ([#436])
- `php artisan app:create-permission` creates a permission and assigns it to roles ([#436])
- The test suite also runs on MySQL and Postgres in CI ([#435])

### Fixed

- On Postgres, creating a role, plan, post, page, category or changelog after seeding no longer fails with a duplicate key error ([#435])
- On Postgres, marking a notification as read with an id that isn't a UUID no longer returns a 500 ([#435])
- A migrated database without users sends the home page to the installer instead of showing a blank page ([#435])
- `GET /logout` also invalidates the session and regenerates the CSRF token, like `POST /auth/logout` ([#435])
- The auth pages no longer request the missing `/storage/auth/background.jpg` ([#435])
- `app:create-role` now assigns the permissions you select ([#436])

## [3.5.0] - 2026-11-09

Wave now runs on Laravel 13, Livewire 4 and Filament 5. Folio, Volt and the theme pages work as before.

### Upgrade notes

- PHP 8.3 or newer is required ([#429])
- Livewire's endpoint moved from `/livewire/...` to `/livewire-{hash}/...`. Update any firewall, CDN or proxy rules that match `/livewire/` ([#429])
- Laravel 13's default cache and Redis key prefixes use dashes (with `APP_NAME=Wave`, `wave-cache-` and `wave-database-` instead of `wave_cache_` and `wave_database_`). If you don't set `CACHE_PREFIX` and `REDIS_PREFIX`, the cache starts cold, and with Redis sessions or queues users are logged out and queued jobs stay under the old prefix. Set both to the old values to avoid this ([#429])
- `app('image')` now returns Laravel's image manager instead of Intervention's. Resolve `Intervention\Image\ImageManager::class` in your own code instead ([#429])
- In `config/livewire.php`, `layout` and `lazy_placeholder` are now `component_layout` and `component_placeholder`. The old keys are ignored ([#429])
- Custom themes and plugins should check the [Livewire 4 upgrade guide](https://livewire.laravel.com/docs/4.x/upgrading): `wire:model` no longer listens to child element events without `.deep`, `wire:model.blur` and `.change` need `.live` to send a request, `<livewire:...>` tags must be self-closing and `wire:transition` modifiers are gone. Volt fragments inside a Filament page should not receive the page's `$slot` and `$attributes`, as in `wave/resources/views/media/index.blade.php` ([#429])
- Third-party Filament plugins need Filament 5 versions before `composer update` will resolve ([#429])

### Changed

- Upgraded to Laravel 13, Livewire 4 and Filament 5, with `devdojo/auth` 2.4.1. `livewire/volt` is now a direct dependency ([#429])
- Generated plugins register their page with `Route::livewire()`, and `make:livewire` keeps generating class-based components ([#429])

### Fixed

- The media manager works under Livewire 4 ([#429])

## [3.4.1] - 2026-11-09

### Fixed

- Paddle webhooks rejected because `PADDLE_WEBHOOK_SECRET` is not set now log a warning ([#431])

## [3.4.0] - 2026-11-09

### Upgrade notes

- `Plan::$features` is a comma-separated string again instead of an array. Use the new `$plan->featureList()` to get the features as a list. Values stored as JSON by older versions are read correctly and rewritten on the next save ([#427])
- Plan limits of `-1`, an empty value or a `null` default in `config/limits.php` all mean unlimited, as documented. Saved limits of `"-1"` used to block the feature ([#427])
- Changing the email address on the profile page clears `email_verified_at`. With `registration_require_email_verification` on, the user gets a verification email and has to verify the new address ([#428])
- New avatars are stored as `avatars/{id}-{random}.png` instead of `avatars/{username}.png`, and the previous upload is deleted when nobody else uses it ([#428])
- Social links must be `http` or `https` URLs. Stored links with other schemes are no longer shown on the public profile ([#428])

### Changed

- Plans with subscriptions can't be deleted from the admin. Turn off "Active" to hide a plan from new customers instead ([#427])
- Plan edits and deletes show up on the pricing page and checkout right away ([#427])

### Fixed

- Plan features from the seeder or older versions show in the admin and on the pricing page again ([#427])
- The "Allow Search Engine Indexing" privacy setting now adds `noindex,nofollow` to the public profile ([#428])
- Avatars no longer collide when a username is reused, and the public profile no longer fails when privacy settings have no visibility key ([#428])

## [3.3.1] - 2026-11-08

### Upgrade notes

- Paddle webhooks are rejected with a 403 when `PADDLE_WEBHOOK_SECRET` isn't set ([#420])
- The Paddle checkout passes the user id to Paddle as `customData` and only accepts transactions that carry it. If you customized `Paddle.Checkout.open` in your checkout view, pass `customData: { user_id }` like the default view does ([#422])
- The API rate limiter and activity logs use `$request->ip()` instead of the `CF-Connecting-IP` header. Behind Cloudflare or another proxy, configure trusted proxies (e.g. `$middleware->trustProxies(at: '*')` in `bootstrap/app.php` when the origin is only reachable through the proxy) so it returns the visitor IP ([#424])
- The `imageable` validation rule only accepts uploaded files and `data:image/` URIs, not file paths ([#425])
- If your `.env` still has the `APP_KEY` from `.env.example`, run `php artisan key:generate`. This logs everyone out and invalidates anything encrypted with the old key ([#426])
- A new migration makes `posts.author_id` and `pages.author_id` nullable and sets them to null when the author is deleted. Rolling it back fails if any post or page already has no author ([#421])
- `laravel/dusk` and `alebatistella/duskapiconf` were removed from `require-dev`. If you have your own Dusk tests, add `laravel/dusk` back ([#423])

### Changed

- The Stripe customer portal requires login, sends users without a subscription to the subscription page and no longer creates a new portal configuration on every visit ([#419])
- Scheduled account deletion cancels the user's Stripe or Paddle subscription first and removes their subscriptions, profile fields and notifications. Posts and pages they wrote are kept without an author ([#421])
- Stricter checks in the Paddle checkout and invoice download ([#422])
- The browser installer generates a new `APP_KEY` when the example key is still in use, and the seeded admin no longer has a fixed remember token ([#426])

### Fixed

- Stripe checkouts paid with async payment methods (SEPA, ACH) or retried after an error now create the subscription. Subscription webhooks handle unmapped prices, terminal statuses and late events ([#419])
- Paddle webhooks no longer return a 500 on every valid request, and redelivered cancellations are acknowledged ([#420])
- The subscription and invoices pages no longer fail right after a Paddle subscription is cancelled, and the subscription page no longer waits on Paddle for 15 seconds ([#420])
- Scheduled account deletion no longer fails for users who wrote posts or pages ([#421])
- The data export includes profile fields again ([#421])
- `HideUnpublishedPosts` is registered through the HTTP kernel like the other web middleware ([#418])

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

[Unreleased]: https://github.com/thedevdojo/wave/compare/3.6.0...HEAD
[3.6.0]: https://github.com/thedevdojo/wave/compare/3.5.0...3.6.0
[3.5.0]: https://github.com/thedevdojo/wave/compare/3.4.1...3.5.0
[3.4.1]: https://github.com/thedevdojo/wave/compare/3.4.0...3.4.1
[3.4.0]: https://github.com/thedevdojo/wave/compare/3.3.1...3.4.0
[3.3.1]: https://github.com/thedevdojo/wave/compare/3.3.0...3.3.1
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
[#418]: https://github.com/thedevdojo/wave/pull/418
[#419]: https://github.com/thedevdojo/wave/pull/419
[#420]: https://github.com/thedevdojo/wave/pull/420
[#421]: https://github.com/thedevdojo/wave/pull/421
[#422]: https://github.com/thedevdojo/wave/pull/422
[#423]: https://github.com/thedevdojo/wave/pull/423
[#424]: https://github.com/thedevdojo/wave/pull/424
[#425]: https://github.com/thedevdojo/wave/pull/425
[#426]: https://github.com/thedevdojo/wave/pull/426
[#427]: https://github.com/thedevdojo/wave/pull/427
[#428]: https://github.com/thedevdojo/wave/pull/428
[#431]: https://github.com/thedevdojo/wave/pull/431
[#429]: https://github.com/thedevdojo/wave/pull/429
[#435]: https://github.com/thedevdojo/wave/pull/435
[#436]: https://github.com/thedevdojo/wave/pull/436
[#438]: https://github.com/thedevdojo/wave/pull/438
[#439]: https://github.com/thedevdojo/wave/pull/439
[#440]: https://github.com/thedevdojo/wave/pull/440
[#441]: https://github.com/thedevdojo/wave/pull/441
