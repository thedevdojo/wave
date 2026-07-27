<?php

use App\Models\User;
use App\Models\Organization;
use App\Models\Invitation;
use Illuminate\Support\Facades\Notification;
use App\Notifications\OrganizationInvitation;
use App\Notifications\OrganizationJoined;

beforeEach(function () {
    $this->admin = User::factory()->create();
    $this->member = User::factory()->create();
});

afterEach(function () {
    $this->admin->forceDelete();
    $this->member->forceDelete();
});

describe('Organization Creation', function () {
    it('requires authentication for settings pages', function () {
        $response = $this->get(route('settings.organization'));
        $response->assertRedirect(route('login'));
    });

    it('loads organization settings page for authenticated user', function () {
        $this->actingAs($this->admin);

        $response = $this->get(route('settings.organization'));
        $response->assertStatus(200);
        $response->assertSee('Organization Settings');
    });

    it('can create an organization', function () {
        $org = Organization::create([
            'name' => 'Acme Labs',
            'user_id' => $this->admin->id,
        ]);

        $this->admin->update(['organization_id' => $org->id]);

        expect($this->admin->isOrganizationAdmin())->toBeTrue();
        expect($this->admin->organization_id)->toBe($org->id);
        expect($org->admin->id)->toBe($this->admin->id);
    });
});

describe('Organization Invitations', function () {
    it('can send an invitation to a registered user', function () {
        Notification::fake();

        $org = Organization::create([
            'name' => 'Acme Labs',
            'user_id' => $this->admin->id,
        ]);
        $this->admin->update(['organization_id' => $org->id]);

        $invitation = Invitation::create([
            'organization_id' => $org->id,
            'email' => $this->member->email,
        ]);

        expect($invitation)->toBeInstanceOf(Invitation::class);
        expect($invitation->email)->toBe($this->member->email);

        // Send Notification
        $this->member->notify(new OrganizationInvitation($invitation, $this->admin, $org));

        Notification::assertSentTo(
            $this->member,
            OrganizationInvitation::class,
            function ($notification) use ($invitation, $org) {
                $data = $notification->toArray($this->member);
                expect($data['title'])->toBe($org->name);
                expect($data['user']['name'])->toBe($this->admin->name);
                expect($data['link'])->toContain('/organizations/invitation/' . $invitation->id);
                return true;
            }
        );
    });
});

describe('Invitation Actions', function () {
    it('allows a user to view their invitation details', function () {
        $org = Organization::create([
            'name' => 'Acme Labs',
            'user_id' => $this->admin->id,
        ]);
        $this->admin->update(['organization_id' => $org->id]);

        $invitation = Invitation::create([
            'organization_id' => $org->id,
            'email' => $this->member->email,
        ]);

        $this->actingAs($this->member);

        $response = $this->get(route('organizations.invitation', ['id' => $invitation->id]));
        $response->assertStatus(200);
        $response->assertSee('Organization Invitation');
        $response->assertSee('Acme Labs');
    });

    it('blocks other users from viewing the invitation details', function () {
        $org = Organization::create([
            'name' => 'Acme Labs',
            'user_id' => $this->admin->id,
        ]);
        $this->admin->update(['organization_id' => $org->id]);

        $invitation = Invitation::create([
            'organization_id' => $org->id,
            'email' => $this->member->email,
        ]);

        $otherUser = User::factory()->create();
        $this->actingAs($otherUser);

        $response = $this->get(route('organizations.invitation', ['id' => $invitation->id]));
        $response->assertRedirect(route('dashboard'));

        $otherUser->forceDelete();
    });

    it('deletes invitation and updates membership when user accepts invitation', function () {
        Notification::fake();

        $org = Organization::create([
            'name' => 'Acme Labs',
            'user_id' => $this->admin->id,
        ]);
        $this->admin->update(['organization_id' => $org->id]);

        $invitation = Invitation::create([
            'organization_id' => $org->id,
            'email' => $this->member->email,
        ]);

        $this->member->update(['organization_id' => $invitation->organization_id]);
        $this->admin->notify(new OrganizationJoined($this->member, $org));

        $invitation->delete();

        expect(Invitation::find($invitation->id))->toBeNull();
        expect($this->member->organization_id)->toBe($org->id);
        expect($this->member->isOrganizationMember())->toBeTrue();

        Notification::assertSentTo(
            $this->admin,
            OrganizationJoined::class,
            function ($notification) use ($org) {
                $data = $notification->toArray($this->admin);
                expect($data['title'])->toBe($org->name);
                expect($data['user']['name'])->toBe($this->member->name);
                return true;
            }
        );
    });

    it('deletes invitation when user declines invitation', function () {
        $org = Organization::create([
            'name' => 'Acme Labs',
            'user_id' => $this->admin->id,
        ]);
        $this->admin->update(['organization_id' => $org->id]);

        $invitation = Invitation::create([
            'organization_id' => $org->id,
            'email' => $this->member->email,
        ]);

        $invitation->delete();

        expect(Invitation::find($invitation->id))->toBeNull();
        expect($this->member->organization_id)->toBeNull();
    });
});
