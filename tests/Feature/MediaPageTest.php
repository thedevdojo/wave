<?php

use App\Models\User;
use Livewire\Mechanisms\HandleComponents\Checksum;

test('the media manager snapshot survives a round trip', function () {
    $this->actingAs(User::factory()->admin()->create());

    $html = $this->get('/admin/media')->assertOk()->getContent();

    preg_match_all('/wire:snapshot="([^"]+)"/', $html, $matches);

    $snapshot = collect($matches[1])
        ->map(fn ($raw) => json_decode(htmlspecialchars_decode($raw), true))
        ->first(fn ($snapshot) => str_starts_with($snapshot['memo']['name'], 'volt-anonymous-fragment'));

    expect($snapshot)->not->toBeNull();

    // the browser sends the snapshot back as JSON, so verify what it decodes to
    Checksum::verify($snapshot);
});
