# Site-wide notifications

Administrators can open **Admin → Notifications**, enter a title, select **Security**, **Marketing**, or **Product update**, and compose a rich-text message. Choose **Send notification** and confirm delivery to all current users. Blog notifications remain part of the publishing workflow and are not a composer label.

These messages appear in the user's Notifications inbox. They are database/in-app notifications, so email preferences do not affect delivery and no email is sent. Each message shows the shared team name/avatar, label, title, rich-text body, and creation time. Configure `wave.notifications.team_name` and `team_avatar` (or `NOTIFICATION_TEAM_NAME`/`NOTIFICATION_TEAM_AVATAR`) to customize the shared public identity. Individual administrator names are not exposed as message senders. Rich text is sanitized before storage/delivery and again when displayed; titles are escaped.

Unread counts appear beside the Notifications links in the desktop and mobile account menus. Badges refresh every 30 seconds and immediately after marking a notification as read. Read broadcast deliveries retain a read receipt so queue retries cannot make them unread again. Existing notification formats retain their current rendering and dismissal behavior.

## Deployment and delivery

Run the application's normal migrations to create `notification_broadcasts`. Run a queue worker for the configured default queue; a `sync` connection executes delivery during the request. The composer confirms that delivery is queued rather than claiming all recipients have already received it.

Delivery uses the configured `auth.providers.users.model`, includes existing users (including administrators), and respects the model's scopes, such as exclusion of soft-deleted accounts. Accounts created after the broadcast are excluded. Recipient rows are inserted in batches of 200 with stable per-broadcast/per-user UUIDs, making job retries idempotent. Failed jobs use the application's standard failed-job monitoring and retry process. `sent_at` records successful completion.

The composer is restricted to administrators, validates labels and message contents, and requires confirmation before sending. Replayed submissions reuse their locked submission UUID to avoid enqueueing the same broadcast twice. Broadcast creation is recorded against the administrator who submitted it in the activity log, with the broadcast ID/category and without message content.
