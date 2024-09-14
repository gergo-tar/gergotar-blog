<?php

use Domain\Tag\Actions\GetTags;
use Domain\Tag\Models\Tag;

test('get-tags', function () {
    $count = 10;
    $tags = Tag::factory($count)->hasSupportedTranslations()->create();

    $list = GetTags::run();

    expect($list->count())->toBe($count);

    // Check if the list contains the right tags.
    $tags->each(function ($tag) use ($list) {
        expect($list->pluck('id'))->toContain($tag->id);
        expect($list->pluck('translation.name'))->toContain($tag->translation->name);
    });
});
