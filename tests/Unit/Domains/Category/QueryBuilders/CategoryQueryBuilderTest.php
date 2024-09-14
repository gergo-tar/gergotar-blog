<?php

use Domain\Category\Models\Category;
use Domain\Category\Models\CategoryTranslation;

test('with-translation-scope', function () {
    $category = Category::factory()->hasDefaultTranslation()->create();
    $translation = $category->translation;

    Category::factory()->hasSupportedTranslations()->create();

    // Call the withTranslation scope.
    $categories = Category::withTranslation()->get();
    // Check if translations are loaded.
    $categories->each(function ($category) {
        expect($category->relationLoaded('translation'))->toBeTrue();
        expect($category->translation)->toBeInstanceOf(CategoryTranslation::class);
    });

    // Check if the category has the right translation.
    $category = $categories->where('id', $category->id)->first();
    expect($category->translation->slug)->toBe($translation->slug);
    expect($category->translation->locale)->toBe($translation->locale);
});

test('with-translations-scope', function () {
    $locales = config('localized-routes.supported_locales');
    $category = Category::factory()->hasSupportedTranslations()->create();
    $translations = $category->translations;

    // Call the withTranslations scope.
    $categories = Category::withTranslations()->get();

    // Check if translations are loaded.
    $categories->each(function ($category) {
        expect($category->relationLoaded('translations'))->toBeTrue();
        $category->translations->each(function ($translation) {
            expect($translation)->toBeInstanceOf(CategoryTranslation::class);
        });
    });

    // Check if the category has the right translations.
    $category = $categories->where('id', $category->id)->first();
    expect($category->translations)->toHaveCount(count($locales));
    $category->translations->each(function ($translation) use ($translations) {
        $selectedTranslation = $translations->where('locale', $translation->locale)->first();
        expect($translation)->toBeInstanceOf(CategoryTranslation::class);
        expect($translation->slug)->toBe($selectedTranslation->slug);
        expect($translation->locale)->toBe($selectedTranslation->locale);
        expect($translation->name)->toBe($selectedTranslation->name);
    });
});

test('where-translation-scope', function () {
    $category = Category::factory()->hasDefaultTranslation()->create();
    $translation = $category->translation;

    Category::factory()->hasSupportedTranslations()->create();

    // Call the whereTranslation scope.
    $category = Category::whereTranslation('slug', $translation->slug)->first();
    // Check if the category has the right translation.
    expect($category->translation)->toBeInstanceOf(CategoryTranslation::class);
    expect($category->translation->slug)->toBe($translation->slug);
    expect($category->translation->locale)->toBe($translation->locale);
});
