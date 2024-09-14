<?php

use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Domain\Blog\Actions\Cache\GetCachedPublishedPost;

test('get-cached-published-post', function () {
    $post = Post::factory()
        ->published()
        ->hasDefaultTranslation()
        ->create();
    $slug = $post->translation->slug;

    $cachedPost = GetCachedPublishedPost::run($slug);

    // Check if the right post is returned.
    expect($cachedPost->id)->toBe($post->id);
    expect($cachedPost->translation->slug)->toBe($slug);

    // Check if the post is cached.
    $cache = Cache::store();
    $cachKey = Post::CACHE_KEY . ".$slug";
    if (method_exists($cache->getStore(), 'tags')) {
        expect(Cache::tags(Post::CACHE_KEY)->has($cachKey))->toBeTrue();
        return;
    }

    expect(Cache::has($cachKey))->toBeTrue();
});
