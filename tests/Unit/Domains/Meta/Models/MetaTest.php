<?php

use Domain\Blog\Models\Post;
use Domain\Meta\Models\Meta;
use Domain\Content\Models\Content;

test('meta-has-post', function () {
    $post = Post::factory()->create();
    $meta = Meta::factory()->forMetable($post)->create();

    // Check if the post is an instance of the Post model.
    expect($meta->metable)->toBeInstanceOf(Post::class);
});

test('meta-has-content', function () {
    $page = Content::factory()->create();
    $meta = Meta::factory()->forMetable($page)->create();

    // Check if the page is an instance of the Page model.
    expect($meta->metable)->toBeInstanceOf(Content::class);
});
