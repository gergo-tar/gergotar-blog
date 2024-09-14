<?php

namespace Domain\Content\Actions;

use Domain\Content\Models\Content;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\StoreTranslations;
use Domain\Content\Actions\Cache\ClearContentSlugCache;

class UpdateContent
{
    use AsAction;

    /**
     * Create the Content record.
     *
     * @param array $data  The data to create the Content record.
     */
    public function handle(Content $content, array $data): Content
    {
        $slug = $content->slug;
        $dataCollection = collect($data);

        // Validation already handled in the FormRequest.
        $content->update(
            $dataCollection->only((new Content())->getFillable())->toArray()
        );

        // Validation already handled in the FormRequest.
        StoreTranslations::run(
            $content,
            $dataCollection->only(config('localized-routes.supported_locales'))->toArray()
        );

        // Clear the cache for the content.
        ClearContentSlugCache::run($slug);

        return $content->loadTranslations([
            'content_id',
            'locale',
            'content',
        ]);
    }
}
