<?php

namespace Domain\Category\Actions;

use Domain\Category\Models\Category;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetCategories
{
    use AsAction;

    /**
     * Get all categories.
     */
    public function handle(array $select = ['*']): Collection
    {
        $translationColumns = ['category_id', 'locale', 'name', 'slug', 'description'];
        // Check if the select array contains the translation columns list.
        if (isset($select['translation'])) {
            $translationColumns = $select['translation'];
            unset($select['translation']);
        }

        return Category::select(array_values($select))
            ->withTranslation($translationColumns)
            ->get();
    }
}
