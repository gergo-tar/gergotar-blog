<?php

namespace Domain\Blog\Actions\Cache;

use Domain\Blog\Models\Post;
use Domain\Blog\Models\PostTranslation;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;

class ClearPostCache
{
    use AsAction;

    /**
     * Clear the cache for posts.
     */
    public function handle(?Post $record = null): void
    {
        $cache = Cache::store();
        // Check if the current cache driver supports tags and flush the cache.
        if (method_exists($cache->getStore(), 'tags')) {
            // Clear cached posts.
            Cache::tags(Post::CACHE_KEY)->flush();

            return;
        }

        // If no record is passed and the cache driver does not support tags, then no caching is used.
        if (! $record) {
            return;
        }

        // Remove the cached post. All of the cached translations.
        $record->loadMissing([
            'translations' => function ($query) {
                $query->select('id', 'post_id', 'locale', 'title', 'slug');
            },
        ])->translations->each(function (PostTranslation $translation) {
            Cache::forget(Post::CACHE_KEY . '.' . $translation->slug);
        });
    }
}
