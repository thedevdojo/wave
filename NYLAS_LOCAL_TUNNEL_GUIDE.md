# Nylas v3 Local Tunnel Development & Testing Guide

This guide describes how to run and test your Nylas v3 OAuth flows and webhook triggers locally on your machine without needing to deploy your application to production.

---

## Prerequisites
1. **A Local Tunnel Tool**:
   - **ngrok**: [ngrok.com](https://ngrok.com/)
   - **Expose**: [expose.dev](https://expose.dev/)
   - **LocalTunnel**: [localtunnel.github.io/www/](https://localtunnel.github.io/www/)
2. **Nylas Developer Account**:
   - Access to your Nylas Dashboard: [dashboard.nylas.com](https://dashboard.nylas.com/)

---

## Step 1: Start Your Local Server and Tunnel

### 1. Run your local server
Start your local PHP/Laravel development server (e.g., using Sail, Herd, Valet, or Artisan):
```bash
php artisan serve
```
By default, this runs on `http://127.0.0.1:8000`.

### 2. Launch your local tunnel
Generate a public HTTPS URL pointing to your local server.

* **Using ngrok:**
  ```bash
  ngrok http 8000
  ```
* **Using Expose:**
  ```bash
  expose share http://127.0.0.1:8000
  ```

Your tunnel tool will display a public HTTPS URL, for example:
`https://abcd-12-34-56.ngrok-free.app`

---

## Step 2: Configure Your `.env` File

Copy your tunnel's public HTTPS URL and use it to update the key environment variables in your local `.env` file:

```env
APP_URL=https://abcd-12-34-56.ngrok-free.app
```

Make sure your Nylas v3 Client ID, API Key, and Webhook Secret are configured as well:
```env
NYLAS_CLIENT_ID=your_nylas_client_id
NYLAS_API_KEY=your_nylas_api_key
NYLAS_WEBHOOK_SECRET=your_nylas_webhook_secret_from_dashboard
```

---

## Step 3: Register OAuth Callback in Nylas Dashboard

To ensure secure redirect after OAuth:
1. Go to your **Nylas Dashboard** -> **Applications** -> Select your application.
2. Under **Authentication Settings** -> **Redirect URIs**, add your tunnel-based callback URL:
   `https://abcd-12-34-56.ngrok-free.app/nylas/callback`
3. Save the changes.

---

## Step 4: Register Webhook in Nylas Dashboard

Nylas uses a GET handshake challenge to verify your webhook endpoint before activating it.

1. In your **Nylas Dashboard**, navigate to **Webhooks**.
2. Click **Create Webhook** or **Add Webhook**.
3. Set the **Destination URL** to:
   `https://abcd-12-34-56.ngrok-free.app/webhooks/nylas`
4. Choose the event triggers you want to listen to (e.g., `message.created`, `message.updated`).
5. Click **Save**.
   - *Nylas will immediately make a `GET` request to your endpoint with a `challenge` parameter.*
   - *Your application's NylasWebhookController automatically echoes this back, and Nylas will mark the webhook as **Active**.*
6. Copy the **Webhook Secret** provided by Nylas and add/update it in your `.env` file:
   ```env
   NYLAS_WEBHOOK_SECRET=the_copied_webhook_secret
   ```

---

## Step 5: Test and Verify the Integration

Now you can test both sending and receiving email locally in real-time!

1. Log in to your local application through your tunnel URL: `https://abcd-12-34-56.ngrok-free.app/login`.
2. Go to the **Integrations** page under Settings: `https://abcd-12-34-56.ngrok-free.app/settings/integrations`.
3. Connect an email account using the **Connect Google Calendar & Email** button.
4. Once connected, two new cards will appear on the Integrations page:
   - **Send Test Email**: Enter a recipient, subject, and body, and hit **Send**. The application will dispatch the email via the Nylas API using the selected grant.
   - **Live Webhook Event Stream**: This is a self-polling card (updates every 5 seconds). Try sending an email to your connected inbox or triggering any webhook event. You will immediately see the events scroll in live on this card! You can expand any card to see the full, verified JSON payload.
