# Authentication activity events

Login and logout listeners record the actor from each Laravel event rather than relying on the default guard's current user. Metadata identifies the event guard, and the record captures the current request's IP address and user agent. Rapid transitions and separate sessions/devices retain their records without a five-minute user/action suppression window.

Only repeated handling of the exact same event object within one request is deduplicated. Separate event instances are retained even when their actor, action, IP, and timestamp match. Request-scoped weak references avoid persistent session identifiers or tokens. Writes are marked handled only after they succeed, so a failed write can be retried.

The existing activity enabled/queue settings are respected for both login and logout. Queued jobs capture context at dispatch time. This does not add cross-request or queue-worker retry idempotency or infer logical event identity from newly allocated event objects. Custom authentication flows still need to emit Laravel authentication events to participate.
