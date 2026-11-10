<?php

it('prints open graph tags for pages without an image', function () {
    $this->get(route('blog'))
        ->assertOk()
        ->assertSee('<meta property="og:title" content="Blog">', false)
        ->assertSee('<meta property="og:description" content="Our Blog">', false)
        ->assertSee('<meta property="og:type" content="website">', false)
        ->assertDontSee('og:image', false)
        ->assertDontSee('itemprop="image"', false);
});

it('still prints the image tags when there is an image', function () {
    $html = view('theme::partials.head', ['seo' => [
        'title' => 'Title',
        'description' => 'Description',
        'image' => 'https://example.com/image.png',
    ]])->render();

    expect($html)
        ->toContain('<meta property="og:title" content="Title">')
        ->toContain('<meta property="og:image" content="https://example.com/image.png">')
        ->toContain('<meta itemprop="image" content="https://example.com/image.png">');
});
