<?php

use Domain\Blog\Models\Post;
use Domain\Category\Models\Category;
use Domain\Category\Models\CategoryTranslation;

test('category-has-posts', function () {
    $count = 3;
    $category = Category::factory()->hasPosts($count)->create();

    // Check if the category has the right amount of posts.
    expect($category->posts->count())->toBe($count);
    // Check if the posts are instances of the Post model.
    $category->posts->each(function ($post) {
        expect($post)->toBeInstanceOf(Post::class);
    });
});

test('category-has-translations', function () {
    $locales = config('localized-routes.supported_locales');
    $category = Category::factory()->hasSupportedTranslations()->create();

    // Check if the category has the right amount of translations.
    expect($category->translations->count())->toBe(count($locales));
    // Check if the translations are instances of the CategoryTranslation model.
    $category->translations->each(function ($translation) use ($locales) {
        expect($locales)->toContain($translation->locale);
        expect($translation)->toBeInstanceOf(CategoryTranslation::class);
    });
});
