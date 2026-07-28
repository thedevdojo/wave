<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NylasService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $apiUri;

    public function __construct()
    {
        $this->clientId = config('services.nylas.client_id') ?? '';
        $this->clientSecret = config('services.nylas.client_secret') ?? '';
        $this->apiUri = rtrim(config('services.nylas.api_uri') ?? 'https://api.us.nylas.com', '/');
    }

    /**
     * Generate the Hosted OAuth redirect URL.
     *
     * @param string $redirectUri The callback URL
     * @param string $state Any state parameter to pass back (e.g., user ID)
     * @return string
     */
    public function getAuthUrl(string $redirectUri, string $state): string
    {
        $queryParams = http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',
            'state' => $state,
        ]);

        return "{$this->apiUri}/v3/connect/auth?{$queryParams}";
    }

    /**
     * Exchange the authorization code for a grant ID.
     *
     * @param string $code
     * @param string $redirectUri The same redirect URI used to request the code
     * @return array|null Returns array containing ['grant_id' => ..., 'email' => ...] or null on failure
     */
    public function exchangeCodeForGrant(string $code, string $redirectUri): ?array
    {
        $url = "{$this->apiUri}/v3/connect/token";

        try {
            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirectUri,
                'code_verifier' => 'nylas',
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Nylas v3 response contains grant_id
                // To get email associated with the grant, or if it is returned in the response, let's look at the response details.
                // According to developer.nylas.com, the token response payload contains:
                // { "access_token": "<ACCESS_TOKEN>", "token_type": "Bearer", "id_token": "<ID_TOKEN>", "grant_id": "<NYLAS_GRANT_ID>", "email": "<EMAIL>" }
                // Let's verify if "email" is in the response. If not, the grant_id can be used as key, or we can fallback to the logged-in user's email, or fetch grant details.
                // Let's make sure we check for email in the response, otherwise we fallback or fetch.
                return [
                    'grant_id' => $data['grant_id'] ?? null,
                    'email' => $data['email'] ?? null,
                ];
            }

            Log::error('Nylas code exchange failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Nylas code exchange exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }

    /**
     * Send an email using a connected grant.
     *
     * @param string $grantId
     * @param array $payload Includes 'subject', 'body', 'to' (array of ['email' => ..., 'name' => ...])
     * @return array|null Returns array containing the sent message response, or null on failure.
     */
    public function sendMessage(string $grantId, array $payload): ?array
    {
        $url = "{$this->apiUri}/v3/grants/{$grantId}/messages/send";

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->clientSecret,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])->post($url, $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Nylas send message failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
        } catch (\Exception $e) {
            Log::error('Nylas send message exception', [
                'message' => $e->getMessage(),
            ]);
        }

        return null;
    }
}
