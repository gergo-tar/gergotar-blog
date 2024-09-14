<?php

namespace Domain\Translation\Actions\Cache;

use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class ClearLocalizedCacheKey
{
    use AsAction;

    /**
     * Clear all localized cache keys.
     *
     * @param  string  $key  The cache key.
     */
    public function handle(string $key): void
    {
        Cache::forget($key);
        // Clear the localized cache keys.
        foreach (config('localized-routes.supported_locales') as $locale) {
            Cache::forget("{$key}.{$locale}");
        }
    }
}
