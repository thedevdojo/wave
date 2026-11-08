<?php

use App\Models\User;
use Devdojo\Auth\Notifications\VerifyEmailWithCode;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;

function jwtPayload(string $token): array
{
    return json_decode(base64_decode(strtr(explode('.', $token)[1], '-_', '+/')), true);
}

function registrationData(array $overrides = []): array
{
    return array_merge([
        'name' => 'Api Register',
        'username' => 'apiregister'.Str::random(6),
        'email' => 'new-user@api-register.test',
        'password' => str_repeat('a', config('wave.auth.min_password_length')),
    ], $overrides);
}

beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    $this->user->forceDelete();
});

describe('POST /api/login', function () {
    it('returns a token whose lifetime matches auth_token_expires', function () {
        config(['wave.api.auth_token_expires' => 15]);

        $response = $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'secret',
        ]);

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJson(['token_type' => 'bearer', 'expires_in' => 15 * 60]);

        $payload = jwtPayload($response->json('access_token'));
        expect($payload['exp'] - $payload['iat'])->toBe(15 * 60);
        expect($payload['sub'])->toBe((string) $this->user->id);
    });

    it('rejects invalid credentials', function () {
        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ])->assertUnauthorized()->assertExactJson(['error' => 'Unauthorized']);
    });
});

describe('POST /api/register', function () {
    afterEach(function () {
        User::withTrashed()->where('email', 'like', '%@api-register.test')->forceDelete();
    });

    it('creates the user and returns a working token', function () {
        $response = $this->postJson('/api/register', registrationData());

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJson(['token_type' => 'bearer', 'expires_in' => config('wave.api.auth_token_expires') * 60]);

        $user = User::where('email', 'new-user@api-register.test')->first();
        expect($user)->not->toBeNull();

        $this->withToken($response->json('access_token'))
            ->getJson('/api/user')
            ->assertOk()
            ->assertJson(['id' => $user->id]);
    });

    it('rejects an invalid email', function () {
        $this->postJson('/api/register', registrationData(['email' => 'not-an-email']))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email')
            ->assertJsonMissingPath('access_token');

        expect(User::where('email', 'not-an-email')->exists())->toBeFalse();
    });

    it('rejects a password shorter than the configured minimum', function () {
        $short = str_repeat('a', config('wave.auth.min_password_length') - 1);

        $this->postJson('/api/register', registrationData(['password' => $short]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('password');

        expect(User::where('email', 'new-user@api-register.test')->exists())->toBeFalse();
    });

    it('rejects an email that is already registered', function () {
        $this->postJson('/api/register', registrationData(['email' => $this->user->email]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    });

    it('rejects a username that is already taken', function () {
        $this->postJson('/api/register', registrationData(['username' => $this->user->username]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('username');
    });

    it('is refused when registrations are disabled', function (string $setting) {
        config(["devdojo.auth.settings.{$setting}" => false]);

        $this->postJson('/api/register', registrationData())
            ->assertForbidden()
            ->assertJsonMissingPath('access_token');

        expect(User::where('email', 'new-user@api-register.test')->exists())->toBeFalse();
    })->with(['registration_enabled', 'enable_email_registration']);
});

describe('email verification', function () {
    beforeEach(function () {
        config(['devdojo.auth.settings.registration_require_email_verification' => true]);
    });

    afterEach(function () {
        User::withTrashed()->where('email', 'like', '%@api-register.test')->forceDelete();
    });

    it('registers the user and sends the verification email without a token', function () {
        Notification::fake();

        $this->postJson('/api/register', registrationData())
            ->assertCreated()
            ->assertJsonStructure(['message'])
            ->assertJsonMissingPath('access_token');

        $user = User::where('email', 'new-user@api-register.test')->firstOrFail();

        expect($user->hasVerifiedEmail())->toBeFalse();
        Notification::assertSentTo($user, VerifyEmailWithCode::class);
    });

    it('does not send a verification email when verification is off', function () {
        config(['devdojo.auth.settings.registration_require_email_verification' => false]);
        Notification::fake();

        $this->postJson('/api/register', registrationData())
            ->assertOk()
            ->assertJsonStructure(['access_token']);

        Notification::assertNothingSent();
    });

    it('refuses to log in an unverified user', function () {
        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'secret',
        ])->assertForbidden()
            ->assertExactJson(['error' => 'Your email address is not verified.']);
    });

    it('logs in a verified user', function () {
        $this->user->markEmailAsVerified();

        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'secret',
        ])->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in']);
    });

    it('still answers wrong passwords with 401', function () {
        $this->postJson('/api/login', [
            'email' => $this->user->email,
            'password' => 'wrong-password',
        ])->assertUnauthorized()->assertExactJson(['error' => 'Unauthorized']);
    });
});

describe('POST /api/refresh', function () {
    it('exchanges a valid token for a new one', function () {
        $token = JWTAuth::fromUser($this->user);

        $response = $this->withToken($token)->postJson('/api/refresh');

        $response->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'expires_in'])
            ->assertJson(['token_type' => 'bearer']);

        $newToken = $response->json('access_token');
        expect($newToken)->not->toBe($token);

        $this->withToken($newToken)
            ->getJson('/api/user')
            ->assertOk()
            ->assertJson(['id' => $this->user->id]);
    });

    it('returns 401 when the token has already been refreshed', function () {
        $token = JWTAuth::fromUser($this->user);

        $this->withToken($token)->postJson('/api/refresh')->assertOk();

        $this->withToken($token)->postJson('/api/refresh')
            ->assertUnauthorized()
            ->assertJsonStructure(['error']);
    });

    it('returns 401 without a token', function () {
        $this->postJson('/api/refresh')
            ->assertUnauthorized()
            ->assertJsonStructure(['error']);
    });

    it('returns 401 for a malformed token', function () {
        $this->withToken('not-a-jwt')->postJson('/api/refresh')
            ->assertUnauthorized()
            ->assertJsonStructure(['error']);
    });
});

describe('POST /api/token', function () {
    it('returns a token whose lifetime matches key_token_expires', function () {
        config(['wave.api.key_token_expires' => 5]);
        $apiKey = $this->user->createApiKey('Token Key');

        $response = $this->postJson('/api/token', ['key' => $apiKey->key]);

        $response->assertOk()
            ->assertJson(['token_type' => 'bearer', 'expires_in' => 5 * 60]);

        $payload = jwtPayload($response->json('access_token'));
        expect($payload['exp'] - $payload['iat'])->toBe(5 * 60);
        expect($payload['sub'])->toBe((string) $this->user->id);
    });

    it('does not change the TTL used for other tokens', function () {
        config(['wave.api.key_token_expires' => 5]);
        $ttl = JWTAuth::factory()->getTTL();

        $this->postJson('/api/token', ['key' => $this->user->createApiKey('Token Key')->key])->assertOk();

        expect(JWTAuth::factory()->getTTL())->toBe($ttl);
    });

    it('requires a key', function () {
        $this->postJson('/api/token')
            ->assertUnprocessable()
            ->assertJsonValidationErrors('key');
    });

    it('returns 401 for an unknown key', function () {
        $this->postJson('/api/token', ['key' => 'invalid_key_that_does_not_exist'])
            ->assertUnauthorized()
            ->assertExactJson(['error' => 'Unauthorized']);
    });

    it('returns 401 when the key belongs to a deleted user', function () {
        $apiKey = $this->user->createApiKey('Orphaned Key');
        $this->user->delete();

        $this->postJson('/api/token', ['key' => $apiKey->key])
            ->assertUnauthorized()
            ->assertExactJson(['error' => 'Unauthorized']);
    });
});

describe('GET /api/user', function () {
    it('does not expose two factor secrets', function () {
        $this->user->forceFill([
            'two_factor_secret' => 'totp-secret',
            'two_factor_recovery_codes' => '["code-1","code-2"]',
        ])->save();

        $this->withToken(JWTAuth::fromUser($this->user))
            ->getJson('/api/user')
            ->assertOk()
            ->assertJson(['id' => $this->user->id, 'email' => $this->user->email])
            ->assertJsonMissingPath('password')
            ->assertJsonMissingPath('two_factor_secret')
            ->assertJsonMissingPath('two_factor_recovery_codes');
    });

    it('requires a token', function () {
        $this->getJson('/api/user')->assertUnauthorized();
    });
});

describe('POST /api/logout', function () {
    it('invalidates the token', function () {
        $token = JWTAuth::fromUser($this->user);

        $this->withToken($token)->postJson('/api/logout')
            ->assertOk()
            ->assertExactJson(['message' => 'Successfully logged out']);

        app('auth')->forgetGuards();

        $this->withToken($token)->getJson('/api/user')->assertUnauthorized();
    });
});

describe('token_api middleware', function () {
    beforeEach(function () {
        Route::middleware(['api', 'token_api'])->get('/api/test-token-api', fn () => auth()->user());
    });

    it('authenticates the owner of the api key', function () {
        $apiKey = $this->user->createApiKey('Middleware Key');

        $this->getJson('/api/test-token-api?token='.$apiKey->key)
            ->assertOk()
            ->assertJson(['id' => $this->user->id]);
    });

    it('rejects an unknown api key', function () {
        $this->getJson('/api/test-token-api?token=unknown')
            ->assertUnauthorized();
    });

    it('rejects requests without credentials', function () {
        $this->getJson('/api/test-token-api')
            ->assertUnauthorized();
    });
});
