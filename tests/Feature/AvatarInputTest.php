<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Volt\Volt;

function pngDataUri(): string
{
    $image = imagecreatetruecolor(2, 2);
    ob_start();
    imagepng($image);

    return 'data:image/png;base64,'.base64_encode(ob_get_clean());
}

it('accepts image data uris and uploaded images', function () {
    expect(Validator::make(['avatar' => pngDataUri()], ['avatar' => 'imageable'])->passes())->toBeTrue()
        ->and(Validator::make(['avatar' => UploadedFile::fake()->image('a.png')], ['avatar' => 'imageable'])->passes())->toBeTrue();
});

it('rejects paths on the server', function () {
    // Keep the fake file around, it's deleted once the object goes away
    $file = UploadedFile::fake()->image('a.png');

    expect(Validator::make(['avatar' => $file->getRealPath()], ['avatar' => 'imageable'])->passes())->toBeFalse();
});

it('saves a cropped avatar from the profile page', function () {
    Storage::fake('public');
    $user = User::factory()->create(['username' => 'avatar_user']);

    Volt::actingAs($user)->test('settings.profile')
        ->set('data.about', 'Hi')
        ->set('avatar', pngDataUri())
        ->call('save')
        ->assertHasNoErrors('avatar');

    Storage::disk('public')->assertExists("avatars/{$user->username}.png");
});

it('does not read a server path as the avatar', function () {
    Storage::fake('public');
    $user = User::factory()->create(['username' => 'avatar_user']);
    $file = UploadedFile::fake()->image('a.png');

    Volt::actingAs($user)->test('settings.profile')
        ->set('data.about', 'Hi')
        ->set('avatar', $file->getRealPath())
        ->call('save')
        ->assertHasErrors('avatar');

    Storage::disk('public')->assertMissing("avatars/{$user->username}.png");
});
