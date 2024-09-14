<?php

namespace Domain\Blog\Actions\Cache;

use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Blog\Actions\GetPublishedPostBySlug;

class GetCachedPublishedPost
{
    use AsAction;

    /**
     * Get the cached published post by slug.
     *
     * @param  string  $slug  The post slug.
     */
    public function handle(string $slug): mixed
    {
        $cache = Cache::store();
        $ttl = config('cache.ttl');
        $cacheKey = $this->cacheKey($slug);

        // Check if the current cache driver supports tags.
        return method_exists($cache->getStore(), 'tags')
            // If it does, use tags to cache the post.
            ? Cache::tags(Post::CACHE_KEY)
            ->remember($cacheKey, $ttl, function () use ($slug) {
                return $this->fetchPost($slug);
            })
            // If it doesn't, cache the post without tags.
            : Cache::remember($cacheKey, $ttl, function () use ($slug) {
                return $this->fetchPost($slug);
            });
    }

    /**
     * Fetch the post by slug.
     *
     * @param  string  $slug  The post slug.
     */
    protected function fetchPost(string $slug): mixed
    {
        $post = GetPublishedPostBySlug::run($slug);

        if (! $post) {
            return null;
        }

        $postLocale = $post->translations->first()->locale;

        // Load the post relationships if the post locale matches the app locale.
        if ($postLocale === app()->getLocale()) {
            $post->translation = $post->translations->first();
            $post->loadRelationships();
        }

        return $post;
    }

    /**
     * Get the cache key for the post.
     *
     * @param  string  $slug  The post slug.
     */
    private function cacheKey(string $slug): string
    {
        return Post::CACHE_KEY . ".$slug";
    }
}
