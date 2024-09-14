<?php

namespace Domain\Project\Actions;

use Domain\Project\Models\Project;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class GetActiveProjects
{
    use AsAction;

    /**
     * Get all categories.
     */
    public function handle(): Collection
    {
        return Cache::remember(
            self::cacheKey() . '.' . app()->getLocale(),
            config('cache.ttl'),
            function () {
                return Project::select(['id', 'url'])
                    ->whereActive()
                    ->withTranslation(['project_id', 'locale', 'title', 'slug', 'description'])
                    ->orderByDesc('created_at')
                    ->get();
            }
        );
    }

    /**
     * Get the cache key for the action.
     */
    public static function cacheKey(): string
    {
        return Project::CACHE_KEY . '.list';
    }
}
