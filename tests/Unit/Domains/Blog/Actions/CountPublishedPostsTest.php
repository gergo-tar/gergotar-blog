<?php

use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Domain\Blog\Actions\CountPublishedPosts;

test('count-published-posts', function () {
    $count = 3;
    Post::factory($count)->published()->create();
    Post::factory($count * 2)->unpublished()->create();

    // Check if the count is correct.
    expect(CountPublishedPosts::run())->toBe($count);

    // Check if the count is in the cache.
    expect(Cache::get(CountPublishedPosts::cacheKey()))->toBe($count);
});
