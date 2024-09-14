<?php

use Domain\Meta\Models\Meta;
use Domain\Content\Models\Content;
use Domain\Content\Models\ContentTranslation;
use Illuminate\Support\Collection;

test('content-has-meta', function () {
    $metas = [
        [
            'key' => 'meta_key',
            'value' => 'meta_value',
        ],
        [
            'key' => 'meta_key_2',
            'value' => 'meta_value_2',
        ],
    ];
    // Set the content with metas.
    $content = Content::factory()->create();
    foreach ($metas as $meta) {
        Meta::factory()->forMetable($content)->create($meta);
    }

    // Check if the right amount of metas are returned.
    expect($content->metas)->toBeInstanceOf(Collection::class);
    expect($content->metas->count())->toBe(count($metas));
    // Check if the right metas are returned.
    $content->metas->each(function ($meta, $key) use ($metas) {
        expect($meta->key)->toBe($metas[$key]['key']);
        expect($meta->value)->toBe($metas[$key]['value']);
    });
});

test('content-has-translation', function () {
    $locales = config('localized-routes.supported_locales');
    $content = Content::factory()->hasSupportedTranslations()->create();

    // Check if the content has the right amount of translations.
    expect($content->translations->count())->toBe(count($locales));
    // Check if the translations are instances of the ContentTranslation model.
    $content->translations->each(function ($translation) use ($locales) {
        expect($locales)->toContain($translation->locale);
        expect($translation)->toBeInstanceOf(ContentTranslation::class);
    });
});
