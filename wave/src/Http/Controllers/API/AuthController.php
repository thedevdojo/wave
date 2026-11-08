<?php

namespace Wave\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
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

        JWTAuth::factory()->setTTL(config('wave.api.auth_token_expires', 60));

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
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
            'last_used_at' => Carbon::now(),
        ]);

        JWTAuth::factory()->setTTL(config('wave.api.key_token_expires', 60));

        return $this->respondWithToken(JWTAuth::fromUser($key->user));
    }

    /**
     * Refresh a token.
     */
    public function refresh(): JsonResponse
    {
        JWTAuth::factory()->setTTL(config('wave.api.auth_token_expires', 60));

        try {
            $token = JWTAuth::parseToken()->refresh();
        } catch (JWTException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     */
    protected function respondWithToken(string $token): JsonResponse
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60,
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
        if (! config('devdojo.auth.settings.registration_enabled', true)) {
            return response()->json([
                'error' => config('devdojo.auth.language.register.registrations_disabled', 'Registrations are currently disabled.'),
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:250|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:'.config('wave.auth.min_password_length'),
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

        JWTAuth::factory()->setTTL(config('wave.api.auth_token_expires', 60));

        return $this->respondWithToken(JWTAuth::fromUser($user));
    }
}
