<?php

use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Domain\Blog\Actions\Cache\GetCachedPublishedPostList;

test('get-cached-published-post-list', function () {
    $limit = 5;
    $posts = Post::factory($limit * 2)->published()->hasDefaultTranslation()->create();

    $cachedPosts = GetCachedPublishedPostList::run(true, $limit);
    // Check if the right posts are returned.
    expect($cachedPosts->count())->toBe($limit);
    $posts->take($limit)->each(function ($post) use ($cachedPosts) {
        $cachedPost = $cachedPosts->firstWhere('id', $post->id);
        expect($cachedPost->id)->toBe($post->id);
        expect($cachedPost->translation->slug)->toBe($post->translation->slug);
    });

    // Check if the posts are cached.
    $cache = Cache::store();
    $cachKey = "page.1.limit.{$limit}";
    if (method_exists($cache->getStore(), 'tags')) {
        expect(Cache::tags(Post::CACHE_KEY)->has($cachKey))->toBeTrue();
        return;
    }

    expect(Cache::has($cachKey))->toBeTrue();
});
