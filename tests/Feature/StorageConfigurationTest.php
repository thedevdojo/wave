<?php

use Illuminate\Support\Facades\Storage;

test('public disk is configured correctly', function () {
    expect(config('filesystems.disks.public'))->not->toBeNull();
    expect(config('filesystems.disks.public.driver'))->toBe('local');
    expect(config('filesystems.disks.public.visibility'))->toBe('public');
});

test('storage symlink is configured', function () {
    $links = config('filesystems.links');

    expect($links)->toHaveKey(public_path('storage'));
    expect($links[public_path('storage')])->toBe(storage_path('app/public'));
});

test('public disk can store and retrieve files', function () {
    Storage::fake('public');

    Storage::disk('public')->put('test.txt', 'test content');

    Storage::disk('public')->assertExists('test.txt');
    expect(Storage::disk('public')->get('test.txt'))->toBe('test content');
});

test('public disk generates correct urls', function () {
    $url = Storage::disk('public')->url('test.jpg');

    expect($url)->toContain('/storage/test.jpg');
});
