<?php

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->admin()->create());

    // the manager builds its own FilesystemManager, so Storage::fake() doesn't reach it
    $root = storage_path('framework/testing/media-'.uniqid());
    config([
        'filesystems.default' => 'local',
        'filesystems.disks.local.root' => $root.'/local',
        'filesystems.disks.public.root' => $root.'/public',
    ]);
    Storage::forgetDisk(['local', 'public']);
    $this->mediaRoot = $root;
});

afterEach(function () {
    File::deleteDirectory($this->mediaRoot);
});

function mediaManager()
{
    $html = test()->get('/admin/media')->assertOk()->getContent();

    preg_match_all('/wire:snapshot="([^"]+)"/', $html, $matches);

    $name = collect($matches[1])
        ->map(fn ($raw) => json_decode(htmlspecialchars_decode($raw), true)['memo']['name'])
        ->first(fn ($name) => str_starts_with($name, 'volt-anonymous-fragment'));

    return Livewire::test($name);
}

it('moves files on the media disk', function () {
    Storage::disk('public')->put('a.txt', 'a');
    Storage::disk('public')->makeDirectory('dir');

    mediaManager()
        ->set('selectedFile', ['name' => 'a.txt', 'type' => 'text/plain'])
        ->set('destinationFolder', 'dir')
        ->call('moveFileOrFolder');

    Storage::disk('public')->assertMissing('a.txt');
    Storage::disk('public')->assertExists('dir/a.txt');
});

it('searches the media disk', function () {
    Storage::disk('public')->put('photos/holiday.jpg', 'x');
    Storage::disk('local')->put('holiday-notes.txt', 'x');

    $results = mediaManager()
        ->set('search', 'holiday')
        ->call('searchStorageForFile')
        ->get('searchResults');

    expect(collect($results)->pluck('relative_path')->all())->toBe(['photos/holiday.jpg']);
});

it('checks the media disk for an existing folder', function () {
    Storage::disk('local')->makeDirectory('reports');

    mediaManager()
        ->set('folderName', 'reports')
        ->call('createNewFolder')
        ->assertHasNoErrors();

    expect(Storage::disk('public')->directoryExists('reports'))->toBeTrue();
});
