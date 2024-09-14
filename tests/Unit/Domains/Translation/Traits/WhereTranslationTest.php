<?php

use Illuminate\Support\Str;
use Domain\Category\Models\Category;

test('category-where-translation-trait', function () {
    // Generate random categories with supported translations.
    Category::factory(10)->hasSupportedTranslations()->create();

    // Create category with the desired name and slug.
    $name = "Category Name en";
    $slug = Str::slug($name);
    $model = Category::factory()->hasDefaultTranslation(compact('name', 'slug'))->create();

    // Check if the where name scope works.
    $result = Category::whereTranslationName($name)->get();
    expect($result->count())->toBe(1);
    expect($result->first()->id)->toBe($model->id);

    // Check if the where slug scope works.
    $result = Category::whereTranslationSlug($slug);
    expect($result->count())->toBe(1);
    expect($result->first()->id)->toBe($model->id);

    // Check if the where translation scope works.
    $result = Category::whereTranslation('locale', config('app.locale'))->get();
    expect($result->count())->toBe(11);
});
