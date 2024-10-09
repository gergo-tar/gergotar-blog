<?php

namespace Domain\Content\Actions;

use Domain\Content\Models\Content;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\StoreTranslations;

class CreateContent
{
    use AsAction;

    /**
     * Create the Content record.
     *
     * @param array $data  The data to create the Content record.
     */
    public function handle(array $data): Content
    {
        $dataCollection = collect($data);

        // Validation already handled in the FormRequest.
        /** @var Content $content */
        $content = Content::create(
            $dataCollection->only((new Content())->getFillable())->toArray()
        );

        // Encond content with json_encode
        $translations = $dataCollection->only(config('localized-routes.supported_locales'))->map(function ($translationData) {
            if ($translationData['content'] && is_array($translationData['content'])) {
                $translationData['content'] = json_encode($translationData['content']);
            }

            return $translationData;
        });

        StoreTranslations::run(
            $content,
            $translations->toArray()
        );

        return $content->loadTranslations([
            'content_id',
            'locale',
            'content',
        ]);
    }
}
