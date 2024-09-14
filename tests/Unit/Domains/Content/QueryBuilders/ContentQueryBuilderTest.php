<?php

use Domain\Content\Models\Content;
use Domain\Content\Models\ContentTranslation;

test('with-translation-scope', function () {
    $content = Content::factory()->hasDefaultTranslation()->create();
    $translation = $content->translation;

    Content::factory()->hasSupportedTranslations()->create();

    // Call the withTranslation scope.
    $contents = Content::withTranslation()->get();
    // Check if translations are loaded.
    $contents->each(function ($content) {
        expect($content->relationLoaded('translation'))->toBeTrue();
        expect($content->translation)->toBeInstanceOf(ContentTranslation::class);
    });

    // Check if the content has the right translation.
    $content = $contents->where('id', $content->id)->first();
    expect($content->translation->slug)->toBe($translation->slug);
    expect($content->translation->locale)->toBe($translation->locale);
});

test('with-translations-scope', function () {
    $locales = config('localized-routes.supported_locales');
    $content = Content::factory()->hasSupportedTranslations()->create();
    $translations = $content->translations;

    // Call the withTranslations scope.
    $contents = Content::withTranslations()->get();

    // Check if translations are loaded.
    $contents->each(function ($content) {
        expect($content->relationLoaded('translations'))->toBeTrue();
        $content->translations->each(function ($translation) {
            expect($translation)->toBeInstanceOf(ContentTranslation::class);
        });
    });

    // Check if the content has the right translations.
    $content = $contents->where('id', $content->id)->first();
    expect($content->translations)->toHaveCount(count($locales));
    $content->translations->each(function ($translation) use ($translations) {
        $selectedTranslation = $translations->where('locale', $translation->locale)->first();
        expect($translation)->toBeInstanceOf(ContentTranslation::class);
        expect($translation->slug)->toBe($selectedTranslation->slug);
        expect($translation->locale)->toBe($selectedTranslation->locale);
        expect($translation->name)->toBe($selectedTranslation->name);
    });
});

test('where-translation-scope', function () {
    $content = Content::factory()->hasDefaultTranslation()->create();
    $translation = $content->translation;

    Content::factory()->hasSupportedTranslations()->create();

    // Call the whereTranslation scope.
    $content = Content::whereTranslation('locale', $translation->locale)->first();
    // Check if the content has the right translation.
    expect($content->translation)->toBeInstanceOf(ContentTranslation::class);
    expect($content->translation->slug)->toBe($translation->slug);
    expect($content->translation->locale)->toBe($translation->locale);
});
