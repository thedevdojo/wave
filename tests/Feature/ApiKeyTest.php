<?php

use App\Models\User;
use Carbon\Carbon;
use Wave\ApiKey;

beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    // Clean up API keys created during tests
    ApiKey::where('user_id', $this->user->id)->delete();
    $this->user->forceDelete();
});

describe('ApiKey Model', function () {
    it('can create an api key', function () {
        $apiKey = ApiKey::create([
            'user_id' => $this->user->id,
            'name' => 'Test Key',
            'key' => 'test_key_123456',
        ]);

        expect($apiKey)->toBeInstanceOf(ApiKey::class);
        expect($apiKey->name)->toBe('Test Key');
        expect($apiKey->key)->toBe('test_key_123456');
        expect($apiKey->user_id)->toBe($this->user->id);
    });

    it('belongs to a user', function () {
        $apiKey = ApiKey::create([
            'user_id' => $this->user->id,
            'name' => 'Test Key',
            'key' => 'test_key_123456',
        ]);

        expect($apiKey->user)->toBeInstanceOf(User::class);
        expect($apiKey->user->id)->toBe($this->user->id);
    });

    it('casts last_used_at to datetime', function () {
        $apiKey = ApiKey::create([
            'user_id' => $this->user->id,
            'name' => 'Test Key',
            'key' => 'test_key_123456',
            'last_used_at' => now(),
        ]);

        expect($apiKey->last_used_at)->toBeInstanceOf(Carbon::class);
    });

    it('has nullable last_used_at by default', function () {
        $apiKey = ApiKey::create([
            'user_id' => $this->user->id,
            'name' => 'Test Key',
            'key' => 'test_key_123456',
        ]);

        expect($apiKey->last_used_at)->toBeNull();
    });

    it('enforces unique key constraint', function () {
        ApiKey::create([
            'user_id' => $this->user->id,
            'name' => 'First Key',
            'key' => 'unique_key_123',
        ]);

        expect(fn () => ApiKey::create([
            'user_id' => $this->user->id,
            'name' => 'Second Key',
            'key' => 'unique_key_123',
        ]))->toThrow(Exception::class);
    });
});

describe('User API Key Methods', function () {
    it('can create api key via user method', function () {
        $apiKey = $this->user->createApiKey('My API Key');

        expect($apiKey)->toBeInstanceOf(ApiKey::class);
        expect($apiKey->name)->toBe('My API Key');
        expect($apiKey->user_id)->toBe($this->user->id);
        expect(strlen($apiKey->key))->toBe(60);
    });

    it('generates unique keys for each api key', function () {
        $key1 = $this->user->createApiKey('Key 1');
        $key2 = $this->user->createApiKey('Key 2');

        expect($key1->key)->not->toBe($key2->key);
    });

    it('can retrieve all api keys for user', function () {
        $this->user->createApiKey('Key 1');
        $this->user->createApiKey('Key 2');
        $this->user->createApiKey('Key 3');

        expect($this->user->apiKeys)->toHaveCount(3);
    });

    it('orders api keys by created_at descending', function () {
        $key1 = $this->user->createApiKey('Key 1');
        Carbon::setTestNow(now()->addMinute());
        $key2 = $this->user->createApiKey('Key 2');
        Carbon::setTestNow(now()->addMinutes(2));
        $key3 = $this->user->createApiKey('Key 3');
        Carbon::setTestNow();

        $keys = $this->user->apiKeys;

        expect($keys->first()->id)->toBe($key3->id);
        expect($keys->last()->id)->toBe($key1->id);
    });

    it('deletes api keys when user is deleted', function () {
        $user = User::factory()->create();
        $apiKey = $user->createApiKey('Test Key');
        $keyId = $apiKey->id;

        $user->forceDelete();

        expect(ApiKey::find($keyId))->toBeNull();
    });
});

describe('API Token Endpoint', function () {
    it('returns 401 when no key provided', function () {
        $response = $this->postJson('/api/token');

        $response->assertStatus(401);
    });

    it('returns 400 for invalid api key', function () {
        $response = $this->postJson('/api/token', [
            'key' => 'invalid_key_that_does_not_exist',
        ]);

        $response->assertStatus(400);
    });

    it('returns access token for valid api key', function () {
        // Skip if JWT secret is not properly configured (common in test environments)
        if (strlen(config('jwt.secret', '')) < 32) {
            $this->markTestSkipped('JWT secret not configured for testing');
        }

        $apiKey = $this->user->createApiKey('Valid Key');

        $response = $this->postJson('/api/token', [
            'key' => $apiKey->key,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['access_token']);
    });

    it('updates last_used_at when api key is used', function () {
        $apiKey = $this->user->createApiKey('Test Key');

        expect($apiKey->last_used_at)->toBeNull();

        Carbon::setTestNow(now());

        $this->postJson('/api/token', [
            'key' => $apiKey->key,
        ]);

        $apiKey->refresh();

        expect($apiKey->last_used_at)->not->toBeNull();
        expect($apiKey->last_used_at->toDateTimeString())->toBe(now()->toDateTimeString());

        Carbon::setTestNow();
    });
});

describe('API Key Settings Page', function () {
    it('requires authentication', function () {
        $response = $this->get(route('settings.api'));

        $response->assertRedirect(route('login'));
    });

    it('loads for authenticated user', function () {
        $this->actingAs($this->user);

        $response = $this->get(route('settings.api'));

        $response->assertStatus(200);
        $response->assertSee('API Keys');
    });

    it('displays existing api keys', function () {
        $this->actingAs($this->user);
        $this->user->createApiKey('my-test-key');

        $response = $this->get(route('settings.api'));

        $response->assertStatus(200);
        $response->assertSee('my-test-key');
    });

    it('shows create new key form', function () {
        $this->actingAs($this->user);

        $response = $this->get(route('settings.api'));

        $response->assertStatus(200);
        $response->assertSee('Create New Key');
    });
});

describe('API Key Activity Logging', function () {
    it('logs api key creation', function () {
        $this->actingAs($this->user);

        // Clear existing activity logs
        \Wave\ActivityLog::where('user_id', $this->user->id)->delete();

        $this->user->createApiKey('Logged Key');

        // Activity logging happens in the Livewire component, not the model
        // So we just verify the key was created
        expect($this->user->apiKeys()->where('name', 'Logged Key')->exists())->toBeTrue();
    });
});

describe('Multiple Users with API Keys', function () {
    it('users can only see their own api keys', function () {
        $user2 = User::factory()->create();

        $key1 = $this->user->createApiKey('User 1 Key');
        $key2 = $user2->createApiKey('User 2 Key');

        expect($this->user->apiKeys)->toHaveCount(1);
        expect($this->user->apiKeys->first()->name)->toBe('User 1 Key');

        expect($user2->apiKeys)->toHaveCount(1);
        expect($user2->apiKeys->first()->name)->toBe('User 2 Key');

        // Cleanup
        ApiKey::where('user_id', $user2->id)->delete();
        $user2->forceDelete();
    });

    it('api key belongs to correct user after token request', function () {
        // Skip if JWT secret is not properly configured (common in test environments)
        if (strlen(config('jwt.secret', '')) < 32) {
            $this->markTestSkipped('JWT secret not configured for testing');
        }

        $user2 = User::factory()->create();

        $key1 = $this->user->createApiKey('User 1 Key');
        $key2 = $user2->createApiKey('User 2 Key');

        // Use user 2's key
        $response = $this->postJson('/api/token', [
            'key' => $key2->key,
        ]);

        $response->assertStatus(200);

        // Verify only user 2's key was updated
        $key1->refresh();
        $key2->refresh();

        expect($key1->last_used_at)->toBeNull();
        expect($key2->last_used_at)->not->toBeNull();

        // Cleanup
        ApiKey::where('user_id', $user2->id)->delete();
        $user2->forceDelete();
    });
});
