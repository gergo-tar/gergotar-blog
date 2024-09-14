<?php

namespace Domain\Tag\Actions;

use Domain\Tag\Models\Tag;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Blog\Actions\Cache\ClearPostCache;
use Domain\Tag\Actions\Cache\ClearTagListCache;
use Domain\Translation\Actions\StoreTranslations;

class UpdateTag
{
    use AsAction;

    /**
     * Create the Tag record.
     *
     * @param array $data  The data to create the Tag record.
     */
    public function handle(Tag $tag, array $data): Tag
    {
        // Validation already handled in the FormRequest.
        StoreTranslations::run(
            $tag,
            collect($data)->only(config('localized-routes.supported_locales'))->toArray()
        );

        // If the Tag is linked to any posts, clear the cache for the posts.
        if ($tag->posts()->exists()) {
            $tag->posts->each(function ($post) {
                ClearPostCache::run($post);
            });
        }

        // Clear the cache for the Tag list.
        ClearTagListCache::run();

        return $tag->loadTranslations([
            'tag_id',
            'locale',
            'name',
            'slug',
            'description',
        ]);
    }
}
