<?php

use App\Models\User;
use Devdojo\Auth\Notifications\VerifyEmailWithCode;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Volt\Volt;

beforeEach(function () {
    Storage::fake('public');

    // Factory usernames can contain dots, which the profile form rejects.
    $this->makeUser = fn (array $attributes = []) => User::factory()->create($attributes + ['username' => 'user_'.Str::random(10)]);

    // A tiny PNG as the cropper sends it: a base64 data URI.
    $this->avatarDataUri = function (): string {
        $image = imagecreatetruecolor(4, 4);
        ob_start();
        imagepng($image);

        return 'data:image/png;base64,'.base64_encode(ob_get_clean());
    };

    $this->saveProfile = function (User $user, array $data = [], ?string $avatar = null) {
        $this->actingAs($user);

        return Volt::test('settings.profile')
            ->set('data.about', 'Hello')
            ->set('avatar', $avatar)
            ->set(collect($data)->mapWithKeys(fn ($value, $key) => ["data.$key" => $value])->all())
            ->call('save');
    };
});

it('gives each user their own avatar file, even after a username is reused', function () {
    $first = ($this->makeUser)(['username' => 'alice']);
    ($this->saveProfile)($first, avatar: ($this->avatarDataUri)())->assertHasNoErrors();

    ($this->saveProfile)($first->fresh(), ['username' => 'alice-renamed'])->assertHasNoErrors();

    $second = ($this->makeUser)(['username' => 'alice']);
    ($this->saveProfile)($second, avatar: ($this->avatarDataUri)())->assertHasNoErrors();

    expect($first->fresh()->avatar)->not->toBe($second->fresh()->avatar);
    Storage::disk('public')->assertExists([$first->fresh()->avatar, $second->fresh()->avatar]);
});

it('removes the previous avatar file when a new one is uploaded', function () {
    $user = ($this->makeUser)();

    ($this->saveProfile)($user, avatar: ($this->avatarDataUri)())->assertHasNoErrors();
    $old = $user->fresh()->avatar;

    ($this->saveProfile)($user->fresh(), avatar: ($this->avatarDataUri)())->assertHasNoErrors();
    $new = $user->fresh()->avatar;

    expect($new)->not->toBe($old);
    Storage::disk('public')->assertMissing($old);
    Storage::disk('public')->assertExists($new);
});

it('keeps avatar files that are not owned by the user', function () {
    Storage::disk('public')->put('demo/default.png', 'default');
    Storage::disk('public')->put('avatars/shared.png', 'shared');

    $user = ($this->makeUser)(['avatar' => 'demo/default.png']);
    ($this->saveProfile)($user, avatar: ($this->avatarDataUri)())->assertHasNoErrors();

    // Older uploads were named after the username, so two users can point at one file.
    $first = ($this->makeUser)(['avatar' => 'avatars/shared.png']);
    ($this->makeUser)(['avatar' => 'avatars/shared.png']);
    ($this->saveProfile)($first, avatar: ($this->avatarDataUri)())->assertHasNoErrors();

    Storage::disk('public')->assertExists(['demo/default.png', 'avatars/shared.png']);
});

it('marks a changed email as unverified and sends a new verification email', function () {
    config(['devdojo.auth.settings.registration_require_email_verification' => true]);
    Notification::fake();

    $user = ($this->makeUser)(['email_verified_at' => now()]);

    ($this->saveProfile)($user, ['email' => 'new-address@example.com'])
        ->assertHasNoErrors()
        ->assertRedirect(route('verification.notice'));

    $user->refresh();
    expect($user->email)->toBe('new-address@example.com');
    expect($user->email_verified_at)->toBeNull();
    expect($user->hasVerifiedEmail())->toBeFalse();
    Notification::assertSentTo($user, VerifyEmailWithCode::class);
});

it('keeps verification when the email is unchanged', function () {
    config(['devdojo.auth.settings.registration_require_email_verification' => true]);
    Notification::fake();

    $user = ($this->makeUser)(['email_verified_at' => now()]);

    ($this->saveProfile)($user, ['name' => 'New Name'])->assertHasNoErrors()->assertNoRedirect();

    expect($user->fresh()->email_verified_at)->not->toBeNull();
    Notification::assertNothingSent();
});

it('does not send a verification email when verification is turned off', function () {
    config(['devdojo.auth.settings.registration_require_email_verification' => false]);
    Notification::fake();

    $user = ($this->makeUser)(['email_verified_at' => now()]);

    ($this->saveProfile)($user, ['email' => 'new-address@example.com'])
        ->assertHasNoErrors()
        ->assertNoRedirect();

    expect($user->fresh()->email_verified_at)->toBeNull();
    Notification::assertNothingSent();
});
