<?php

use Domain\Tag\Models\Tag;
use Domain\Tag\Actions\CreateTag;

test('create-tag', function () {
    // Set tag data for each supported locale.
    $data = [];
    $locales = config('localized-routes.supported_locales');
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => "Tag Name {$locale}",
            'description' => "Tag Description {$locale}",
        ];
    }

    $tag = CreateTag::run($data);

    // Check if the tag is created correctly.
    expect($tag)->toBeInstanceOf(Tag::class);
    // Check if tag has the right number of translations.
    expect($tag->translations)->toHaveCount(count($locales));
    // Check if the tag translations are created correctly.
    foreach ($locales as $locale) {
        $translation = $tag->translations->where('locale', $locale)->first();
        expect($translation->name)->toBe("Tag Name {$locale}");
        expect($translation->description)->toBe("Tag Description {$locale}");
    }
});
