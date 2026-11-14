# Access after subscription cancellation

Cancelling a subscription marks only that subscription as cancelled. Access is then derived from the newest remaining active subscription, ordered by creation time and then ID. Retiring a plan from new sales does not revoke an existing subscriber's access.

Billing roles are roles referenced by plans, plus the configured default user role. Cancellation replaces these with the remaining subscription's plan role, or the default role when no active subscriptions remain. Separately assigned roles, such as administrator roles that are not mapped to plans, are preserved.

The status update and role reconciliation run in one database transaction. Cancellations lock the account row before selecting the remaining subscription. This does not enforce a single active subscription or serialize other checkout/plan-change paths. Repeated cancellations reconcile access again; signed provider webhook retries remain safe.
