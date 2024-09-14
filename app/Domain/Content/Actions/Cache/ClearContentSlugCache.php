<?php

namespace Domain\Content\Actions\Cache;

use Domain\Content\Models\Content;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\Cache\ClearLocalizedCacheKey;

class ClearContentSlugCache
{
    use AsAction;

    /**
     * Clear the cache for a specific content slug.
     *
     * @param string $slug  The content slug.
     */
    public function handle(string $slug): void
    {
        // Clear the localized cache keys.
        ClearLocalizedCacheKey::run(Content::CACHE_KEY . ".{$slug}");
    }
}
