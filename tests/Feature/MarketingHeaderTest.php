<?php

use App\Models\User;

it('links logged in users straight to the dashboard', function () {
    $this->actingAs(User::factory()->create())
        ->get('/')
        ->assertOk()
        ->assertSee('href="'.route('dashboard').'"', false)
        ->assertDontSee('href="'.route('login').'"', false);
});

it('shows login and sign up to guests', function () {
    User::factory()->create();

    $this->get('/')
        ->assertOk()
        ->assertSee('href="'.route('login').'"', false)
        ->assertSee('href="'.route('register').'"', false);
});
