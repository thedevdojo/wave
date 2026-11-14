# Proposal: store API-key digests and show secrets only at creation

Status: design proposal for maintainer discussion. This PR changes documentation only; it does not change authentication, migrations, or existing keys.

## Problem

Wave currently persists reusable API-key secrets in `api_keys.key`, looks them up directly in the token controller and `TokenMiddleware`, and displays existing secrets on the API settings page. A database or backup disclosure therefore exposes credentials that can immediately be used to authenticate. Users need a way to copy a newly created key, but authentication does not require recovering its secret later.

## Proposed behavior

- Keep cryptographically random, high-entropy keys and their existing external format where possible.
- Display a newly generated secret once, immediately after creation. Explain that it cannot be displayed again and can be replaced if lost.
- Persist a SHA-256 digest in an indexed, unique 64-character `key_hash` column. Compare or query a digest computed from the submitted secret. High-entropy machine-generated secrets are different from human passwords; this follows the approach used by [Laravel Sanctum](https://laravel.com/docs/13.x/sanctum#issuing-api-tokens) without requiring Wave to adopt Sanctum.
- Keep key IDs, ownership, names, creation dates, usage timestamps, and deletion/revocation behavior unchanged. Optional non-secret prefixes or last characters can identify a key in the UI.
- Centralize lookup in one API-key verification method so token exchange and `TokenMiddleware` cannot accidentally disagree.
- Hide the digest from model serialization. Keep the transient newly issued secret out of logs, persisted UI state, activity metadata, queues, and subsequent key-list responses. Return it only to the authenticated creator over HTTPS.

## Migration and compatibility

Existing client credentials can be retained: compute the digest of each existing plaintext key, retaining its row ID and original key value on the client. After the cutover, clients still send the same secret and the server compares its digest.

A staged rollout should add and backfill `key_hash` in bounded batches before switching readers and writers. The final cutover must remove plaintext values and the ability to display existing secrets. Any temporary dual-write or plaintext fallback must have an explicit removal step; retaining plaintext indefinitely defeats this change. Existing backups still contain old secrets and must be considered separately when deciding whether rotation is needed.

The public `createApiKey()` return contract needs an explicit compatibility decision. Prefer a creation result containing the persisted key metadata plus a transient plaintext secret, rather than placing the secret on the serializable model. Themes and plugins that currently read `$key->key` or query the plaintext column need an upgrade note and examples. The settings UI must be updated at the same time, including every maintained theme with API-key management.

Rollback after deleting plaintext cannot restore recoverable secrets from hashes. Plan the release accordingly; do not implement a down migration that promises recovery of the original secrets.

## Acceptance criteria for an implementation

- New keys authenticate through every supported path; persisted rows contain no reusable plaintext secret.
- A pre-upgrade key still authenticates after backfill, with its ID and usage history unchanged.
- Missing, malformed, invalid, and revoked keys are rejected without exposing secrets or digests.
- Ownership and quota checks remain in place, and another user cannot retrieve or delete a key.
- Key-list, edit, activity-log, and serialized model responses expose neither plaintext secrets nor digests.
- The creation UI reveals the secret once; a subsequent request cannot recover it.
- Concurrent creation still enforces digest uniqueness.
- Upgrade notes describe the model/creation-result changes and theme/plugin migration.

## Maintainer decisions requested

Is this storage change appropriate for the next release? Which compatibility window and creation-result API should Wave support? Should users rotate existing keys instead of retaining them through digest backfill?
