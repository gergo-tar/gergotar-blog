<?php

namespace Domain\Category\Actions;

use Illuminate\Support\Collection;
use Domain\Category\Models\Category;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCategoryList
{
    use AsAction;

    /**
     * Get the Tag list for select list.
     */
    public function handle(): Collection
    {
        return Cache::remember(self::cacheKey() . '.' . app()->getLocale(), config('cache.ttl'), function () {
            return GetCategories::run(
                ['id', 'translation' => ['category_id', 'locale', 'name']]
            )->pluck('translation.name', 'id');
        });
    }

    /**
     * Get the cache key for the action.
     */
    public static function cacheKey(): string
    {
        return Category::CACHE_KEY . '.list';
    }
}
