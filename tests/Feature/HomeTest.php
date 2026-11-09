<?php

use App\Models\User;

it('Home returns a successful response', function () {
    User::factory()->create();

    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('Ship in Days');
});

it('sends the home page to the installer while there are no users', function () {
    $this->get('/')->assertRedirect(route('wave.install'));
});
