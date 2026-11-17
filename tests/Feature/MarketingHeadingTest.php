<?php

use App\Models\User;
use Wave\Category;

it('shows the profile name and username in the guest heading as text', function () {
    $user = User::factory()->create(['name' => 'Ann <b>Bold</b> & Co']);

    $this->get('/profile/'.$user->username)
        ->assertOk()
        ->assertSee('Ann &lt;b&gt;Bold&lt;/b&gt; &amp; Co', false)
        ->assertDontSee('<b>Bold</b>', false);
});

it('shows a category name in the blog heading once encoded', function () {
    $category = Category::factory()->create(['name' => 'Tips & <i>Tricks</i>']);

    $this->get('/blog/'.$category->slug)
        ->assertOk()
        ->assertSee('Tips &amp; &lt;i&gt;Tricks&lt;/i&gt; Articles', false)
        ->assertDontSee('&amp;amp;', false)
        ->assertDontSee('<i>Tricks</i>', false);
});

it('keeps the line break in the home page features heading', function () {
    User::factory()->create();

    $this->get('/')->assertOk()->assertSee('Master the Seas of <br> Product Development', false);
});
