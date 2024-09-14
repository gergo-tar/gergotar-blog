<?php

namespace Domain\Blog\Actions\Cache;

use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Blog\Actions\CountPublishedPosts;

class ClearCountPublishedPostsCache
{
    use AsAction;

    /**
     * Clear the cache for the count of published posts.
     */
    public function handle(): void
    {
        Cache::forget(CountPublishedPosts::cacheKey());
    }
}
