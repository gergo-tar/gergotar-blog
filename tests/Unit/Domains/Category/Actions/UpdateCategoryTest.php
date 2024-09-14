<?php

use Domain\Category\Models\Category;
use Domain\Category\Actions\UpdateCategory;
use Domain\Category\Actions\GetCategoryList;

test('update-category', function () {
    $category = Category::factory()->hasSupportedTranslations()->create();

    // Just get the list to Store it in the cache.
    $cacheKey = GetCategoryList::cacheKey() . '.' . app()->getLocale();
    GetCategoryList::run();
    // Check if the list is in the cache.
    expect(cache()->has($cacheKey))->toBeTrue();

    // Set the new category data for each supported locale.
    $data = [];
    $locales = config('localized-routes.supported_locales');
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => "Category Name {$locale} Updated",
            'description' => "Category Description {$locale} Updated",
        ];
    }

    $category = UpdateCategory::run($category, $data);

    // Check if the category is updated correctly.
    expect($category)->toBeInstanceOf(Category::class);
    // Check if category has the right number of translations after update.
    expect($category->translations)->toHaveCount(count($locales));
    // Check if the category translations are updated correctly.
    foreach ($locales as $locale) {
        $translation = $category->translations->where('locale', $locale)->first();
        expect($translation->name)->toBe("Category Name {$locale} Updated");
        expect($translation->description)->toBe("Category Description {$locale} Updated");
    }

    // Check if the list is cleared from the cache.
    expect(cache()->has($cacheKey))->toBeFalse();
});
