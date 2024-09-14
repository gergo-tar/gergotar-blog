<?php

namespace Domain\Project\Actions\Cache;

use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Project\Actions\GetActiveProjects;
use Domain\Translation\Actions\Cache\ClearLocalizedCacheKey;

class CleareActiveProjectCache
{
    use AsAction;

    /**
     * Clear the cache for the project list.
     */
    public function handle(): void
    {
        // Clear the localized cache keys.
        ClearLocalizedCacheKey::run(GetActiveProjects::cacheKey());
    }
}
