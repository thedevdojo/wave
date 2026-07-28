<?php

namespace App\Http\Controllers;

use App\Models\NylasAccount;
use App\Services\NylasService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NylasController extends Controller
{
    protected NylasService $nylasService;

    public function __construct(NylasService $nylasService)
    {
        $this->nylasService = $nylasService;
    }

    /**
     * Start the Nylas OAuth connection flow.
     */
    public function connect(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $redirectUri = route('nylas.callback');
        $state = (string) $user->id;

        $authUrl = $this->nylasService->getAuthUrl($redirectUri, $state);

        return redirect()->away($authUrl);
    }

    /**
     * Handle the Nylas OAuth callback.
     */
    public function callback(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $code = $request->query('code');
        $state = $request->query('state');

        if (!$code) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Authorization code not found.');
        }

        // Optional: validate state parameter matches current user ID
        if ($state && (string) $user->id !== $state) {
            return redirect()->route('settings.integrations')
                ->with('error', 'Invalid state parameter.');
        }

        $redirectUri = route('nylas.callback');
        $result = $this->nylasService->exchangeCodeForGrant($code, $redirectUri);

        if ($result && isset($result['grant_id'])) {
            // Nylas response might not have 'email', fallback to user email
            $email = $result['email'] ?? $user->email;

            // Store in database
            NylasAccount::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'email' => $email,
                ],
                [
                    'grant_id' => $result['grant_id'],
                ]
            );

            return redirect()->route('settings.integrations')
                ->with('success', 'Successfully connected your email and calendar through Nylas.');
        }

        return redirect()->route('settings.integrations')
            ->with('error', 'Failed to connect your account. Please try again.');
    }

    /**
     * Disconnect/remove a Nylas account.
     */
    public function disconnect(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $account = $user->nylasAccounts()->where('id', $id)->first();

        if ($account) {
            $account->delete();
            return redirect()->route('settings.integrations')
                ->with('success', 'Successfully disconnected the account.');
        }

        return redirect()->route('settings.integrations')
            ->with('error', 'Account not found.');
    }
}
