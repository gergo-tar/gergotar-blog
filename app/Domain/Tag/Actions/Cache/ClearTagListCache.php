<?php

namespace Domain\Tag\Actions\Cache;

use Domain\Tag\Actions\GetTagList;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\Cache\ClearLocalizedCacheKey;

class ClearTagListCache
{
    use AsAction;

    /**
     * Clear the cache for the tag list.
     */
    public function handle(): void
    {
        ClearLocalizedCacheKey::run(GetTagList::cacheKey());
    }
}
