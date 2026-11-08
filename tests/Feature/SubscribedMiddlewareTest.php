<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

beforeEach(function () {
    Route::middleware(['web', 'subscribed'])->get('/subscribers-only', fn () => 'subscriber content');
});

it('sends guests to the login page and back afterwards', function () {
    $this->get('/subscribers-only')->assertRedirect(route('login'));

    expect(session('url.intended'))->toBe(url('/subscribers-only'));
});

it('sends users without a subscription to billing', function () {
    $this->actingAs(User::factory()->create())
        ->get('/subscribers-only')
        ->assertRedirect(route('billing'));
});

it('lets admins through', function () {
    $this->actingAs(User::factory()->admin()->create())
        ->get('/subscribers-only')
        ->assertOk()
        ->assertSee('subscriber content');
});
