<?php

use Domain\Tag\Models\Tag;
use Domain\Tag\Actions\GetTagList;
use Illuminate\Support\Facades\Cache;
use Domain\Tag\Actions\Cache\ClearTagListCache;

test('clear-tag-list-cache', function () {
    $count = 10;
    $categories = Tag::factory($count)->hasSupportedTranslations()->create();

    $list = GetTagList::run();

    // Check if the list is in the cache.
    $cacheKey = GetTagList::cacheKey();
    expect(Cache::get($cacheKey . '.' . app()->getLocale()))->toBe($list);

    // Clear the list from the cache.
    ClearTagListCache::run();

    // Check if the list is no longer in the cache.
    expect(Cache::get($cacheKey . '.' . app()->getLocale()))->toBeNull();
});
