<?php

use Domain\Blog\Models\Post;
use Domain\Meta\Models\Meta;

test('where-not-has-key-scope', function () {
    $post = Post::factory()->create();
    Meta::factory()->forMetable($post)->create(['key' => 'key1', 'metable_id' => $post->id]);
    Meta::factory()->forMetable($post)->create(['key' => 'key2', 'metable_id' => $post->id]);
    Meta::factory()->forMetable($post)->create(['key' => 'key3', 'metable_id' => $post->id]);

    // Get all metas that do not have the keys 'key1' and 'key2'.
    $metas = Meta::whereNotHasKey(['key1', 'key2'])->get();

    // Check if the metas are filtered correctly.
    expect($metas->count())->toBe(1);
    expect($metas->first()->key)->toBe('key3');
});
