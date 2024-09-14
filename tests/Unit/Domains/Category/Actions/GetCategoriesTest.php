<?php

use Domain\Category\Actions\GetCategories;
use Domain\Category\Models\Category;

test('get-categories', function () {
    $count = 10;
    $categories = Category::factory($count)->hasSupportedTranslations()->create();

    $list = GetCategories::run();

    expect($list->count())->toBe($count);

    // Check if the list contains the right categories.
    $categories->each(function ($category) use ($list) {
        expect($list->pluck('id'))->toContain($category->id);
        expect($list->pluck('translation.name'))->toContain($category->translation->name);
    });
});
