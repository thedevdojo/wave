<?php

namespace Wave\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Closure;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Validation\Validator as ValidatorContract;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Wave\ApiKey;

class AuthController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login', 'token', 'register', 'refresh']),
        ];
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login(): JsonResponse
    {
        $credentials = request(['email', 'password']);
        $guard = auth('api');

        if (! $guard->validate($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = $guard->getLastAttempted();

        // Same rule as the web side's verified middleware.
        if (! $user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Your email address is not verified.'], 403);
        }

        return $this->respondWithToken(
            fn () => JWTAuth::fromUser($user),
            config('wave.api.auth_token_expires', 60)
        );
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout(): JsonResponse
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Get a JWT for the owner of an API key.
     */
    public function token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->respondWithErrors($validator);
        }

        $key = ApiKey::where('key', $request->key)->first();

        if (! $key?->user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $key->update([
            'last_used_at' => now(),
        ]);

        return $this->respondWithToken(
            fn () => JWTAuth::fromUser($key->user),
            config('wave.api.key_token_expires', 60)
        );
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        try {
            return $this->respondWithToken(
                fn () => JWTAuth::parseToken()->refresh(),
                config('wave.api.auth_token_expires', 60)
            );
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
    }

    /**
     * Issue a token that lives for $ttl minutes and return the token array structure.
     *
     * The TTL lives on the shared JWT factory, so it is put back afterwards to keep
     * it from leaking into anything else that issues a token in the same process.
     */
    protected function respondWithToken(Closure $issue, int $ttl): JsonResponse
    {
        $factory = JWTAuth::factory();
        $previousTtl = $factory->getTTL();
        $factory->setTTL($ttl);

        try {
            $token = $issue();
        } finally {
            $factory->setTTL($previousTtl);
        }

        if (! $token) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $ttl * 60,
        ]);
    }

    protected function respondWithErrors(ValidatorContract $validator): JsonResponse
    {
        return response()->json([
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors(),
        ], 422);
    }

    public function register(Request $request): JsonResponse
    {
        if (! config('devdojo.auth.settings.registration_enabled', true)
            || ! config('devdojo.auth.settings.enable_email_registration', true)) {
            return response()->json([
                'error' => config('devdojo.auth.language.register.registrations_disabled', 'Registrations are currently disabled.'),
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:'.config('wave.auth.min_password_length', 8),
        ]);

        if ($validator->fails()) {
            return $this->respondWithErrors($validator);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
        ]);

        // Sends the verification email when it's required, like the web registration.
        event(new Registered($user));

        if (! $user->hasVerifiedEmail()) {
            return response()->json([
                'message' => 'Registration successful. Please verify your email address before logging in.',
            ], 201);
        }

        return $this->respondWithToken(
            fn () => JWTAuth::fromUser($user),
            config('wave.api.auth_token_expires', 60)
        );
    }
}
