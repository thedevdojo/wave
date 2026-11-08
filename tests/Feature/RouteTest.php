<?php

use App\Models\User;

use function Pest\Laravel\get;

// The route datasets point at the demo content, so load all of it.
beforeEach(function () {
    $this->seed();
    $this->registerPageRoutes();
});

it('responds with 200 for all routes', function (string $route) {
    $response = get($route);
    $response->assertStatus(200);
})->with('routes');

test('responds with 200 for all auth routes', function ($url) {
    $user = User::find(1);

    $this->actingAs($user);

    $response = $this->get($url);

    $response->assertStatus(200);
})->with('authroutes');
