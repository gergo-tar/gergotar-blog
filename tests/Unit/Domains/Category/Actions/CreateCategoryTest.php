<?php

use Domain\Category\Models\Category;
use Domain\Category\Actions\CreateCategory;

test('create-category', function () {
    // Set category data for each supported locale.
    $data = [];
    $locales = config('localized-routes.supported_locales');
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => "Category Name {$locale}",
            'description' => "Category Description {$locale}",
        ];
    }

    $category = CreateCategory::run($data);

    // Check if the category is created correctly.
    expect($category)->toBeInstanceOf(Category::class);
    // Check if category has the right number of translations.
    expect($category->translations)->toHaveCount(count($locales));
    // Check if the category translations are created correctly.
    foreach ($locales as $locale) {
        $translation = $category->translations->where('locale', $locale)->first();
        expect($translation->name)->toBe("Category Name {$locale}");
        expect($translation->description)->toBe("Category Description {$locale}");
    }
});
