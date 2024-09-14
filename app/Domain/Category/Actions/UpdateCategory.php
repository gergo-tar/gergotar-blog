<?php

namespace Domain\Category\Actions;

use Domain\Category\Models\Category;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Blog\Actions\Cache\ClearPostCache;
use Domain\Translation\Actions\StoreTranslations;
use Domain\Category\Actions\Cache\CleareCategoryListCache;

class UpdateCategory
{
    use AsAction;

    /**
     * Create the Category record.
     *
     * @param array $data  The data to create the Category record.
     */
    public function handle(Category $category, array $data): Category
    {
        // Validation already handled in the FormRequest.
        StoreTranslations::run(
            $category,
            collect($data)->only(config('localized-routes.supported_locales'))->toArray()
        );

        // If the Category is linked to any posts, clear the cache for the posts.
        if ($category->posts()->exists()) {
            $category->posts->each(function ($post) {
                ClearPostCache::run($post);
            });
        }

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
