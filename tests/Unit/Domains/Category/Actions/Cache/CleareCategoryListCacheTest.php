<?php

use Domain\Category\Models\Category;
use Illuminate\Support\Facades\Cache;
use Domain\Category\Actions\GetCategoryList;
use Domain\Category\Actions\Cache\CleareCategoryListCache;

test('clear-category-list-cache', function () {
    $count = 10;
    Category::factory($count)->hasSupportedTranslations()->create();

    $list = GetCategoryList::run();

    // Check if the list is in the cache.
    $cacheKey = GetCategoryList::cacheKey() . '.' . app()->getLocale();
    expect(Cache::get($cacheKey))->toBe($list);

    // Clear the list from the cache.
    CleareCategoryListCache::run();

    // Check if the list is no longer in the cache.
    expect(Cache::get($cacheKey))->toBeNull();
});
