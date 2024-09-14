<?php

use Domain\Tag\Models\Tag;
use Domain\Tag\Actions\UpdateTag;
use Domain\Tag\Actions\GetTagList;

test('update-tag', function () {
    $tag = Tag::factory()->hasSupportedTranslations()->create();

    // Just get the list to Store it in the cache.
    $cacheKey = GetTagList::cacheKey() . '.' . app()->getLocale();
    GetTagList::run();
    // Check if the list is in the cache.
    expect(cache()->has($cacheKey))->toBeTrue();

    // Set the new tag data for each supported locale.
    $data = [];
    $locales = config('localized-routes.supported_locales');
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => "Tag Name {$locale} Updated",
            'description' => "Tag Description {$locale} Updated",
        ];
    }

    $tag = UpdateTag::run($tag, $data);

    // Check if the tag is updated correctly.
    expect($tag)->toBeInstanceOf(Tag::class);
    // Check if tag has the right number of translations after update.
    expect($tag->translations)->toHaveCount(count($locales));
    // Check if the tag translations are updated correctly.
    foreach ($locales as $locale) {
        $translation = $tag->translations->where('locale', $locale)->first();
        expect($translation->name)->toBe("Tag Name {$locale} Updated");
        expect($translation->description)->toBe("Tag Description {$locale} Updated");
    }
});
