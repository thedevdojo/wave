<?php

// tests/Feature/RouteResponseTest.php

use function Pest\Laravel\get;

it('responds with 200 for all routes', function (string $route) {
    $response = get($route);
    $response->assertStatus(200);
})->with('routes');

test('responds with 200 for all auth routes', function ($url) {
    $user = \App\Models\User::find(1);

    $this->actingAs($user);

    $response = $this->get($url);

    $response->assertStatus(200);
})->with('authroutes');