<?php

namespace Domain\Tag\Actions;

use Domain\Tag\Models\Tag;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetTags
{
    use AsAction;

    /**
     * Get all tags.
     */
    public function handle(array $select = ['*']): Collection
    {
        $translationColumns = ['tag_id', 'locale', 'name', 'slug', 'description'];
        // Check if the select array contains the translation columns list.
        if (isset($select['translation'])) {
            $translationColumns = $select['translation'];
            unset($select['translation']);
        }

        return Tag::select(array_values($select))
            ->withTranslation($translationColumns)
            ->get();
    }
}
