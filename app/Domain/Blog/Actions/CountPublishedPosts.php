<?php

namespace Domain\Blog\Actions;

use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class CountPublishedPosts
{
    use AsAction;

    /**
     * Count Published Posts.
     */
    public function handle(): int
    {
        return Cache::remember(static::cacheKey(), now()->addMinutes(5), function () {
            return $this->getPublishedPostCount();
        });
    }

    /**
     * Get the cache key for the action.
     */
    public static function cacheKey(): string
    {
        return Post::CACHE_KEY . '-count-published';
    }

    /**
     * Get the Published post count.
     */
    public function getPublishedPostCount(): int
    {
        return Post::select('id')->wherePublished()->count();
    }
}
