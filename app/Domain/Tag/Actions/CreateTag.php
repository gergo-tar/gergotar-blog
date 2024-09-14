<?php

namespace Domain\Tag\Actions;

use Domain\Tag\Models\Tag;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Tag\Actions\Cache\ClearTagListCache;
use Domain\Translation\Actions\StoreTranslations;

class CreateTag
{
    use AsAction;

    /**
     * Create the Tag record.
     *
     * @param array $data  The data to create the Tag record.
     */
    public function handle(array $data): Tag
    {
        // Validation already handled in the FormRequest.
        /** @var Tag $tag */
        $tag = Tag::create();

        StoreTranslations::run(
            $tag,
            collect($data)->only(config('localized-routes.supported_locales'))->toArray()
        );

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
