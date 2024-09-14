<?php

use Domain\Category\Models\Category;
use Domain\Category\Models\CategoryTranslation;

test('category-translation-has-category', function () {
    $category = Category::factory()->create();
    $translation = CategoryTranslation::factory()->create([
        'category_id' => $category->id,
    ]);

    // Check if the translation has the right category.
    expect($translation->category)->toBeInstanceOf(Category::class);
    expect($translation->category_id)->toBe($category->id);
});
