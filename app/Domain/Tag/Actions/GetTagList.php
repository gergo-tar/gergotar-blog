<?php

namespace Domain\Tag\Actions;

use Domain\Tag\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTagList
{
    use AsAction;

    /**
     * Get the Tag list for select list.
     */
    public function handle(): Collection
    {
        return Cache::remember(static::cacheKey() . '.' . app()->getLocale(), config('cache.ttl'), function () {
            return GetTags::run(
                ['id', 'translation' => ['tag_id', 'locale', 'name']]
            )->pluck('translation.name', 'id');
        });
    }

    /**
     * Get the cache key for the action.
     */
    public static function cacheKey(): string
    {
        return Tag::CACHE_KEY . '.list';
    }
}
