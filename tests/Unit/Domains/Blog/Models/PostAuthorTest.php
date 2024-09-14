<?php

use Domain\Blog\Models\Post;
use Domain\Blog\Models\PostAuthor;

test('post-author-has-posts', function () {
    $count = 3;
    $author = PostAuthor::factory()->hasPosts($count)->create();

    // Check if the right amount of posts are returned.
    expect($author->posts->count())->toBe($count);
    // Check if the right posts are returned.
    $author->posts->each(function ($post) use ($author) {
        expect($post)->toBeInstanceOf(Post::class);
        expect($post->author->id)->toBe($author->id);
    });
});
