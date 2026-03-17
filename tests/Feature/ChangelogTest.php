<?php

use App\Models\User;
use Wave\Changelog;

beforeEach(function () {
    $this->user = User::factory()->create();
});

afterEach(function () {
    $this->user->forceDelete();
});

describe('Changelog Model', function () {
    it('can create a changelog entry', function () {
        $changelog = Changelog::create([
            'title' => 'New Feature Release',
            'description' => 'We added something cool',
            'body' => 'Full description of the new feature...',
        ]);

        expect($changelog)->toBeInstanceOf(Changelog::class);
        expect($changelog->title)->toBe('New Feature Release');
        expect($changelog->description)->toBe('We added something cool');
        expect($changelog->body)->toBe('Full description of the new feature...');
    });

    it('has fillable attributes', function () {
        $changelog = new Changelog();

        expect($changelog->getFillable())->toBe(['title', 'description', 'body']);
    });

    it('has timestamps', function () {
        $changelog = Changelog::create([
            'title' => 'Test',
            'description' => 'Test desc',
            'body' => 'Test body',
        ]);

        expect($changelog->created_at)->not->toBeNull();
        expect($changelog->updated_at)->not->toBeNull();
    });

    it('can have many users who have read it', function () {
        $changelog = Changelog::create([
            'title' => 'Test',
            'description' => 'Test desc',
            'body' => 'Test body',
        ]);

        $user2 = User::factory()->create();

        $changelog->users()->attach([$this->user->id, $user2->id]);

        expect($changelog->users)->toHaveCount(2);
        expect($changelog->users->pluck('id')->toArray())->toContain($this->user->id);
        expect($changelog->users->pluck('id')->toArray())->toContain($user2->id);

        $user2->forceDelete();
    });
});

describe('User Changelog Relationship', function () {
    it('user can have many changelogs read', function () {
        $changelog1 = Changelog::create([
            'title' => 'Feature 1',
            'description' => 'Desc 1',
            'body' => 'Body 1',
        ]);

        $changelog2 = Changelog::create([
            'title' => 'Feature 2',
            'description' => 'Desc 2',
            'body' => 'Body 2',
        ]);

        $this->user->changelogs()->attach([$changelog1->id, $changelog2->id]);

        expect($this->user->changelogs)->toHaveCount(2);
    });
});

describe('Changelog Read Endpoint', function () {
    it('requires authentication', function () {
        $response = $this->post(route('changelog.read'));

        $response->assertRedirect(route('login'));
    });

    it('marks all unread changelogs as read', function () {
        $this->actingAs($this->user);

        // Clear existing changelogs for isolated test
        Changelog::query()->delete();

        $changelog1 = Changelog::create([
            'title' => 'Feature 1',
            'description' => 'Desc 1',
            'body' => 'Body 1',
        ]);

        $changelog2 = Changelog::create([
            'title' => 'Feature 2',
            'description' => 'Desc 2',
            'body' => 'Body 2',
        ]);

        expect($this->user->changelogs)->toHaveCount(0);

        $this->post(route('changelog.read'));

        $this->user->refresh();
        expect($this->user->changelogs)->toHaveCount(2);
    });

    it('does not duplicate already read changelogs', function () {
        $this->actingAs($this->user);

        // Clear existing changelogs for isolated test
        Changelog::query()->delete();

        $changelog1 = Changelog::create([
            'title' => 'Feature 1',
            'description' => 'Desc 1',
            'body' => 'Body 1',
        ]);

        // Mark as already read
        $this->user->changelogs()->attach($changelog1->id);

        $changelog2 = Changelog::create([
            'title' => 'Feature 2',
            'description' => 'Desc 2',
            'body' => 'Body 2',
        ]);

        $this->post(route('changelog.read'));

        $this->user->refresh();
        expect($this->user->changelogs)->toHaveCount(2);
    });

    it('handles empty changelogs gracefully', function () {
        $this->actingAs($this->user);

        // Clear any existing changelogs
        Changelog::query()->delete();

        $response = $this->post(route('changelog.read'));

        $response->assertStatus(200);
    });
});
