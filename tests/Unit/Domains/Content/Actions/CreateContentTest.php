<?php

use Domain\Content\Models\Content;
use Domain\Content\Actions\CreateContent;

test('create-content', function () {
    $title = 'Content Title';
    $data = compact('title');

    $locales = config('localized-routes.supported_locales');
    // Set category data for each supported locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'content' => fake()->paragraph,
        ];
    }

    $content = CreateContent::run($data);

    expect($content)->toBeInstanceOf(Content::class);
    expect($content->title)->toBe($title);
    expect($content->translations)->toHaveCount(count($locales));
    foreach ($locales as $locale) {
        $translation = $content->translations->where('locale', $locale)->first();
        expect($translation->content)->toBe($data[$locale]['content']);
    }
});
