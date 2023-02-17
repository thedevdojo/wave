<?php

test('available routes', function ($url) {
    $response = $this->get($url);

    $response->assertStatus(200);
})->with('routes');

test('available auth routes', function ($url) {
    $user = \App\Models\User::find(1);

    $this->actingAs($user);

    $response = $this->get($url);

    $response->assertStatus(200);
})->with('authroutes');
