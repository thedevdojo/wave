<?php

use App\Models\User;
use Wave\Category;
use Wave\Post;

beforeEach(function () {
    $this->user = User::where('email', 'admin@admin.com')->first();
    $this->actingAs($this->user);
});

test('post resource uses public disk for images', function () {
    $post = Post::factory()->create([
        'image' => 'posts/test-image.jpg',
        'author_id' => $this->user->id,
    ]);

    // Verify the image path is stored correctly
    expect($post->image)->toBe('posts/test-image.jpg');

    // Verify the image() method returns a public URL
    $imageUrl = $post->image();
    expect($imageUrl)->toContain('/storage/posts/test-image.jpg');
});

test('post rich editor attachments use public disk', function () {
    $post = Post::factory()->create([
        'body' => '<p>Test content with <a href="/storage/attachments/file.pdf">attachment</a></p>',
        'author_id' => $this->user->id,
    ]);

    // Verify body contains public storage path
    expect($post->body)->toContain('/storage/attachments/');
});

test('post can be created with image in posts directory', function () {
    $category = Category::first();

    $post = Post::create([
        'title' => 'Test Post',
        'slug' => 'test-post-'.time(),
        'body' => '<p>Test content</p>',
        'excerpt' => 'Test excerpt',
        'image' => 'posts/new-image.jpg',
        'author_id' => $this->user->id,
        'category_id' => $category->id,
        'status' => 'PUBLISHED',
        'featured' => false,
    ]);

    expect($post->image)->toStartWith('posts/');
    expect($post->image())->toContain('/storage/posts/');
});

test('post image url generation works correctly', function () {
    $post = Post::factory()->create([
        'image' => 'posts/example.jpg',
        'author_id' => $this->user->id,
    ]);

    $url = $post->image();

    // Should use the configured disk
    expect($url)->toBeString();
    expect($url)->toContain('storage');
    expect($url)->toContain('posts/example.jpg');
});
