<?php

use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Domain\Blog\Actions\CountPublishedPosts;
use Domain\Blog\Actions\Cache\ClearCountPublishedPostsCache;

test('clear-count-published-posts-cache', function () {
    $count = 3;
    Post::factory($count)->published()->create();
    Post::factory($count * 2)->unpublished()->create();
    $cacheKey = (new CountPublishedPosts())->cacheKey();

    // Put the count in the cache.
    CountPublishedPosts::run();

    // Check if the count is in the cache.
    expect(Cache::get($cacheKey))->toBe($count);

    // Clear the count from the cache.
    ClearCountPublishedPostsCache::run();

    // Check if the count is no longer in the cache.
    expect(Cache::get($cacheKey))->toBeNull();
});
