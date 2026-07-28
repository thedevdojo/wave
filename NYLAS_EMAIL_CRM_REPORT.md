# Nylas v3 Email Client Integration Report: Building a Gmail-Like CRM Tool

This report outlines the comprehensive architecture, API operations, real-time webhook infrastructure, database schema, and implementation roadmap required to build a fully featured, Gmail-like email tool integrated directly into your Laravel Wave CRM application.

With this setup, users can:
- **Connect** their inbox via secure OAuth.
- **Read & View** real-time folders (Inbox, Sent, Drafts), email threads, and details.
- **Send & Reply** to emails seamlessly using direct delivery or synchronized drafts.
- **Store copies** of all messages, threads, and attachments inside our local CRM database to index them, enable lightning-fast full-text search, and automatically link conversations to existing CRM Leads, Deals, or Contacts.

---

## 1. Architectural Blueprint: Syncing vs. On-Demand Loading

To build a robust and high-performing CRM email client, we use a hybrid architecture:
1. **Local Database Synchronization (Metadata and Text Content)**:
   - When an email account is first connected, and subsequently via real-time webhooks, we ingest email metadata (sender, recipient, subject, thread ID, timestamps) and the raw message body (plain text & HTML) into our database.
   - This approach has immense advantages:
     - **Performance**: Listing emails and loading threads is instantaneous (queries run against our local database).
     - **CRM Automation**: Incoming and outgoing emails are automatically linked to contacts based on email addresses. Your Sales team can view a contact's complete email history directly from their CRM profile.
     - **Searchability**: Full-text searching of emails is executed locally.
2. **On-Demand Loading (Large Attachments and Uncached Content)**:
   - While text content and metadata are cached, attachments can be quite large. We fetch attachment content on-demand from the Nylas API when a user requests a download. This keeps our local storage lean.

```
┌─────────────────┐             ┌───────────────┐             ┌─────────────────┐
│                 │ (1) Sync    │               │ (2) Read/   │                 │
│   Nylas API     │────────────>│  CRM Database │────────────>│   CRM Frontend  │
│   (v3 Engine)   │  Webhooks   │  (MySQL/PG)   │  Compose UI │  (Livewire/CSS) │
│                 │             │               │             │                 │
└─────────────────┘             └───────────────┘             └─────────────────┘
         ▲                                                             │
         │ (3) Direct Send API                                         │
         └─────────────────────────────────────────────────────────────┘
```

---

## 2. Nylas v3 API: Core Email Operations

Nylas v3 introduces a streamlined, RESTful API structure built around **Grants** (which represent connected user accounts). Below are the key endpoints and integration methods using Laravel's native HTTP Client.

### A. Listing / Reading Emails from the Inbox
To display an inbox list, we retrieve messages from Nylas using the user's `grant_id`. The endpoint returns messages in reverse chronological order.

* **Endpoint**: `GET https://api.us.nylas.com/v3/grants/{grant_id}/messages`
* **Query Parameters**:
  - `limit`: Number of messages to retrieve (default: 50, max: 200).
  - `page_token`: Token used to retrieve the next page.
  - `unread`: Filter by unread status (true/false).
  - `any_email`: Search sender/recipient email addresses.

#### Laravel Service Method:
```php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class NylasEmailService
{
    protected string $apiKey;
    protected string $apiUri;

    public function __construct()
    {
        $this->apiKey = config('services.nylas.client_secret'); // In our config, client_secret corresponds to NYLAS_API_KEY
        $this->apiUri = rtrim(config('services.nylas.api_uri', 'https://api.us.nylas.com'), '/');
    }

    /**
     * Retrieve messages for a given grant ID.
     */
    public function getMessages(string $grantId, array $queryParams = []): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Accept' => 'application/json',
        ])->get("{$this->apiUri}/v3/grants/{$grantId}/messages", $queryParams);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Nylas API Error fetching messages: " . $response->body());
    }
}
```

---

### B. Sending Emails Direct
To send an email directly (without first drafting it on the provider side), you execute a single POST request.

* **Endpoint**: `POST https://api.us.nylas.com/v3/grants/{grant_id}/messages`

#### Laravel Service Method:
```php
    /**
     * Send an email message.
     */
    public function sendMessage(string $grantId, array $data): array
    {
        // Data format expected:
        // [
        //    'subject' => 'CRM Proposal',
        //    'body' => '<p>Hello, here is the proposal we discussed.</p>',
        //    'to' => [['email' => 'client@example.com', 'name' => 'John Client']],
        //    'reply_to' => [['email' => 'sender@example.com', 'name' => 'My CRM User']]
        // ]
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->apiKey,
            'Content-Type' => 'application/json',
        ])->post("{$this->apiUri}/v3/grants/{$grantId}/messages", $data);

        if ($response->successful()) {
            return $response->json();
        }

        throw new \Exception("Nylas API Error sending message: " . $response->body());
    }
```

---

### C. Replying and Threading
A "Gmail-like" tool groups related messages into logical conversations. In Nylas, related messages are given the same `thread_id`.

When replying to an existing email, you should:
1. Fetch the original message metadata to obtain its `thread_id`.
2. Include the original message ID in the `reply_to_message_id` parameter of your send payload. Nylas handles the SMTP threading headers (`In-Reply-To` and `References`) automatically!

#### Reply Send Payload:
```json
{
  "subject": "Re: Discussion about CRM integration",
  "body": "Thank you for the update. Let us schedule a meeting next week.",
  "to": [
    {
      "email": "client@example.com",
      "name": "John Client"
    }
  ],
  "reply_to_message_id": "original-nylas-message-id"
}
```

---

## 3. Real-Time Webhooks & Security Handshake

Webhooks are crucial. Instead of resource-intensive polling, Nylas sends a push notification the moment an email is received, opened, or updated on any connected account.

### A. Trigger Types for Email
- `message.created`: Fired when a new email arrives in the inbox or is sent.
- `message.updated`: Fired when an email is marked read/unread, starred, or moved to a folder.
- `thread.replied`: Fired when a new reply is received in an ongoing thread.

---

### B. The Challenge Handshake (GET Route)
When you register a webhook URL in Nylas (e.g., `https://your-domain.com/webhooks/nylas`), Nylas immediately verifies your endpoint by sending an HTTP `GET` request with a `challenge` query parameter. Your application **must** return the raw value of this parameter back in plain text with a `200 OK` status within 10 seconds.

#### Handshake PHP Controller:
```php
use Illuminate\Http\Request;

public function handleWebhook(Request $request)
{
    // 1. Challenge Handshake Verification (GET request)
    if ($request->isMethod('get') && $request->has('challenge')) {
        return response($request->query('challenge'), 200)
            ->header('Content-Type', 'text/plain');
    }
}
```

---

### C. Signature Verification (POST Route)
To ensure incoming notifications are genuinely sent by Nylas and not fake/spoofed requests, Nylas signs every payload.
- It calculates an HMAC-SHA256 signature using your **Webhook Client Secret** and the raw POST request body.
- It attaches this signature as the `X-Nylas-Signature` header.
- In your webhook controller, you must calculate the HMAC signature locally and compare it in a timing-safe manner.

#### Complete Webhook Controller Example:
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Jobs\SyncNewEmailJob;

class NylasWebhookController extends Controller
{
    /**
     * Handle the Nylas Webhook requests.
     */
    public function handle(Request $request)
    {
        // 1. Handshake Handlers (GET)
        if ($request->isMethod('get') && $request->has('challenge')) {
            return response($request->query('challenge'), 200)
                ->header('Content-Type', 'text/plain');
        }

        // 2. Webhook Event Handling (POST)
        $signature = $request->header('X-Nylas-Signature');
        $webhookSecret = config('services.nylas.webhook_secret'); // Store this in your .env

        if (!$signature || !$webhookSecret) {
            Log::warning('Nylas Webhook missing signature or secret configuration.');
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $rawBody = $request->getContent();
        $calculatedSignature = hash_hmac('sha256', $rawBody, $webhookSecret);

        // Constant-time string comparison to prevent timing attacks
        if (!hash_equals($signature, $calculatedSignature)) {
            Log::warning('Nylas Webhook signature verification failed.', [
                'received' => $signature,
                'calculated' => $calculatedSignature
            ]);
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        // 3. Process Validated CloudEvent Payload
        $payload = json_decode($rawBody, true);

        // Nylas payloads are grouped under a data container
        if (isset($payload['data']) && isset($payload['type'])) {
            $eventType = $payload['type']; // e.g. "message.created"
            $eventData = $payload['data'];
            $grantId = $eventData['grant_id'] ?? null;
            $objectId = $eventData['object']['id'] ?? null; // Message or Thread ID

            Log::info("Nylas webhook received event [{$eventType}] for Grant [{$grantId}]");

            if ($eventType === 'message.created' && $grantId && $objectId) {
                // Dispatch background job to fetch details from Nylas and store in database
                SyncNewEmailJob::dispatch($grantId, $objectId);
            }
        }

        return response()->json(['status' => 'success'], 200);
    }
}
```

---

## 4. CRM Database Schema Design

To persist email records, link them to Leads/Contacts automatically, and implement fast pagination and indexing, we implement the following database schema.

```
                  ┌─────────────────┐
                  │      Users      │
                  └─────────────────┘
                           │ 1
                           │
                           │ *
                  ┌─────────────────┐
                  │ NylasAccounts   │
                  └─────────────────┘
                           │ 1
                           │
                           │ *
                  ┌─────────────────┐
                  │  EmailThreads   │
                  └─────────────────┘
                           │ 1
                           │
                           │ *
                  ┌─────────────────┐
                  │  EmailMessages  │
                  └─────────────────┘
                           │ 1
                           │
                           │ *
                  ┌─────────────────┐
                  │EmailAttachments │
                  └─────────────────┘
```

### Migration 1: Email Threads Table
```php
Schema::create('email_threads', function (Blueprint $table) {
    $table->id();
    $table->string('nylas_thread_id')->unique();
    $table->unsignedBigInteger('nylas_account_id');
    $table->string('subject')->nullable();
    $table->timestamp('last_message_at')->nullable();
    $table->timestamps();

    $table->foreign('nylas_account_id')->references('id')->on('nylas_accounts')->onDelete('cascade');
});
```

### Migration 2: Email Messages Table
```php
Schema::create('email_messages', function (Blueprint $table) {
    $table->id();
    $table->string('nylas_message_id')->unique();
    $table->unsignedBigInteger('email_thread_id')->nullable();
    $table->unsignedBigInteger('nylas_account_id');

    // Sender
    $table->string('from_email');
    $table->string('from_name')->nullable();

    // Recipients (Stored as JSON for multiple recipients)
    $table->json('to');  // Array of ['email' => ..., 'name' => ...]
    $table->json('cc')->nullable();
    $table->json('bcc')->nullable();

    // Body Text
    $table->string('subject')->nullable();
    $table->text('body_snippet')->nullable(); // Short text overview for lists
    $table->longText('body_html')->nullable(); // Rich Text body content

    // CRM Contact/Lead Association (Optional)
    $table->unsignedBigInteger('crm_contact_id')->nullable(); // Automatically linked on ingestion

    // Metadata
    $table->boolean('is_read')->default(false);
    $table->boolean('is_draft')->default(false);
    $table->timestamp('received_at')->nullable();
    $table->timestamps();

    $table->foreign('email_thread_id')->references('id')->on('email_threads')->onDelete('set null');
    $table->foreign('nylas_account_id')->references('id')->on('nylas_accounts')->onDelete('cascade');
    $table->index('from_email');
});
```

### Migration 3: Email Attachments Table
```php
Schema::create('email_attachments', function (Blueprint $table) {
    $table->id();
    $table->string('nylas_attachment_id')->unique();
    $table->unsignedBigInteger('email_message_id');
    $table->string('filename');
    $table->string('content_type');
    $table->bigInteger('size'); // file size in bytes
    $table->timestamps();

    $table->foreign('email_message_id')->references('id')->on('email_messages')->onDelete('cascade');
});
```

---

## 5. Step-by-Step Implementation Roadmap

Below is a complete, structured development roadmap to integrate this Gmail-like tool successfully into Laravel Wave:

### Phase 1: Database Setup
1. Create the migrations described in Section 4.
2. Build Eloquent models for `EmailThread`, `EmailMessage`, and `EmailAttachment`.
3. Add helper relationships in the standard `NylasAccount` model:
   ```php
   public function messages() {
       return $this->hasMany(EmailMessage::class);
   }
   public function threads() {
       return $this->hasMany(EmailThread::class);
   }
   ```

### Phase 2: Webhook Endpoint Integration
1. Define Webhook routes in `routes/api.php` or `routes/web.php`:
   ```php
   Route::match(['get', 'post'], 'webhooks/nylas', [NylasWebhookController::class, 'handle'])
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]); // Disable CSRF for external Nylas API push
   ```
2. Implement signature verification logic securely.
3. Configure your local tunnel (e.g., using **ngrok** or **Expose**) during development to test the live challenge handshake with the Nylas webhook portal.

### Phase 3: Background Jobs for Sync
1. Create `SyncNewEmailJob`:
   - It fetches the full message details from the Nylas API via `NylasEmailService`.
   - Checks if the sender/recipient corresponds to an existing Lead/Contact in your database.
   - Saves the email into `email_messages` table and links it to the `crm_contact_id` if a match is found.
2. Build an **Initial Sync Command**:
   - A command like `php artisan nylas:initial-sync {account_id}` that can be dispatched immediately after OAuth is successful, ensuring the user's recent email history is populated in the CRM inbox instantly.

### Phase 4: CRM User Interface (Vite, Tailwind, Livewire)
1. **Inbox View**: A clean, split-pane layout showing folder selections (Inbox, Sent, Archive, Drafts) on the left, a list of threads with subjects, snippet previews, and dates in the middle, and the open thread on the right.
2. **Conversation View**: Renders the thread of emails sequentially. Offers visual markers for read/unread, inline replies, and downloadable attachment pills.
3. **Draft Compose Component**: A beautiful popup rich text compiler (integrating TipTap) with fields for `To`, `Cc`, and `Subject`, with fully configured attachments upload functionality.

---

This architecture is optimized for speed, security, and tight CRM cohesion, giving you full control over the communication stream while using Nylas to reliably power all mail transfers.
