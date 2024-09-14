<?php

namespace Domain\Content\Actions;

use Domain\Content\Models\Content;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class GetContentBySlug
{
    use AsAction;

    /**
     * Get Content by Slug.
     *
     * @param  string  $slug  The post slug.
     */
    public function handle(string $slug): ?Content
    {
        return Cache::remember($this->getCacheKey($slug), config('cache.ttl'), function () use ($slug) {
            // @phpstan-ignore-next-line
            return Content::select(['id', 'title', 'slug'])
                ->whereSlug($slug)
                ->withTranslation(['content_id', 'locale', 'content'])
                ->withTranslationMetas(['metable_type', 'metable_id', 'key', 'value'])
                ->first();
        });
    }

    /**
     * Get the localized cache key.
     */
    private function getCacheKey(string $slug): string
    {
        return Content::CACHE_KEY . ".{$slug}." . app()->getLocale();
    }
}
