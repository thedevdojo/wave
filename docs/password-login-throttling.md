# Password login attempt limits

Wave limits failed web-guard password logins through Laravel's authentication events. The shared authentication package's normal login, custom components using its trait, and direct `Auth::attempt()` calls receive the same protection without changing vendor files.

Defaults under `wave.auth` are five failures per normalized email/IP pair (`login_max_attempts`), fifty failures across accounts per IP (`login_ip_max_attempts`), and a sixty-second window (`login_decay_seconds`). Email-first navigation does not consume attempts. Correct credentials are refused while a matching failure limit is exhausted. The lockout uses Laravel's translated `auth.throttle` message and fires its `Lockout` event.

A successful login clears the email/IP failure counter but preserves the IP-wide spraying budget. Starting a two-factor challenge does not clear the failure counter until login completes. Other guards and credentials without a password do not increment these password counters. API-key and passkey endpoints need their own request limits.

Counters use Laravel's configured rate-limiter cache and trusted request IP. The email/IP identifier and IP identifier are hashed in cache keys. Production workers and servers should share a persistent limiter cache, and trusted proxies should be narrowly configured.

This works with the installed authentication package. The package's separate upstream [PR #219](https://github.com/thedevdojo/auth/pull/219) adds trait-level throttling; Wave does not edit vendor files or depend on that unreleased change. These counters have a separate namespace so they can coexist with the package's counters.
