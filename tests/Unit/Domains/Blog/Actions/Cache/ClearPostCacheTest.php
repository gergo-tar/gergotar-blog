<?php

use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Domain\Blog\Actions\Cache\ClearPostCache;

test('clear-post-cache', function () {
    $post = Post::factory()->unpublished()->hasDefaultTranslation()->create();

    $cache = Cache::store();
    $cacheKey = Post::CACHE_KEY . "-{$post->translation->slug}";
    // Check if the current cache driver supports tags.
    if (method_exists($cache->getStore(), 'tags')) {
        // Store the post in the cache.
        Cache::tags(Post::CACHE_KEY)->put($cacheKey, $post, 60);
        // Check if the post is in the cache.
        expect(Cache::tags(Post::CACHE_KEY)->get($cacheKey))->toBe($post);
        // Clear the post from the cache.
        ClearPostCache::run();
        // Check if the post is no longer in the cache.
        expect(Cache::tags(Post::CACHE_KEY)->get($cacheKey))->toBeNull();

        return;
    }

    // Store the post in the cache.
    Cache::remember($cacheKey, 60, function () use ($post) {
        return $post;
    });

    // Check if the post is in the cache.
    expect(Cache::get($cacheKey))->toBe($post);
    // Clear the post from the cache.
    ClearPostCache::run($post);
    // Check if the post is no longer in the cache.
    expect(Cache::get($cacheKey))->toBeNull();
});
