<?php

namespace Domain\Category\Actions;

use Domain\Category\Models\Category;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\StoreTranslations;
use Domain\Category\Actions\Cache\CleareCategoryListCache;

class CreateCategory
{
    use AsAction;

    /**
     * Create the Category record.
     *
     * @param array $data  The data to create the Category record.
     */
    public function handle(array $data): Category
    {
        // Validation already handled in the FormRequest.
        /** @var Category $category */
        $category = Category::create();

        StoreTranslations::run(
            $category,
            collect($data)->only(config('localized-routes.supported_locales'))->toArray()
        );

        // Clear the cache for the category list.
        CleareCategoryListCache::run();

        return $category->loadTranslations([
            'category_id',
            'locale',
            'name',
            'slug',
            'description',
        ]);
    }
}
