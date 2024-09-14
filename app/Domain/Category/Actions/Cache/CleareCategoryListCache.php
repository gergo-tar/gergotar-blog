<?php

namespace Domain\Category\Actions\Cache;

use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Category\Actions\GetCategoryList;
use Domain\Translation\Actions\Cache\ClearLocalizedCacheKey;

class CleareCategoryListCache
{
    use AsAction;

    /**
     * Clear the cache for the category list.
     */
    public function handle(): void
    {
        ClearLocalizedCacheKey::run(GetCategoryList::cacheKey());
    }
}
