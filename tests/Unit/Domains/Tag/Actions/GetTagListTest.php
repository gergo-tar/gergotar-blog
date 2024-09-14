<?php

use Domain\Tag\Actions\GetTagList;
use Domain\Tag\Models\Tag;

test('get-tag-list', function () {
    $count = 10;
    $tags = Tag::factory($count)->hasSupportedTranslations()->create();
    $translations = $tags->pluck('translation.name', 'id');

    $list = GetTagList::run();

    // Check if the list contains the right tags.
    expect($list->count())->toBe($count);
    expect($tags->pluck('translation.name', 'id')->toArray())->toBe($list->toArray());
    expect($translations->toArray())->toBe($list->toArray());

    // Check if the list is in the cache.
    expect(cache()->has(GetTagList::cacheKey() . '.' . app()->getLocale()))->toBeTrue();
});
