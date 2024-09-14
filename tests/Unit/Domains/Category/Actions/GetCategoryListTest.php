<?php

use Domain\Category\Models\Category;
use Illuminate\Support\Facades\Cache;
use Domain\Category\Actions\GetCategoryList;

test('get-category-list', function () {
    $count = 10;
    $categories = Category::factory($count)->hasSupportedTranslations()->create();
    $translations = $categories->pluck('translation.name', 'id');

    $list = GetCategoryList::run();

    // Check if the list contains the right categories.
    expect($list->count())->toBe($count);
    expect($categories->pluck('translation.name', 'id')->toArray())->toBe($list->toArray());
    expect($translations->toArray())->toBe($list->toArray());

    // Check if the list is in the cache.
    expect(Cache::get(GetCategoryList::cacheKey() . '.' . app()->getLocale()))->toBe($list);
});
