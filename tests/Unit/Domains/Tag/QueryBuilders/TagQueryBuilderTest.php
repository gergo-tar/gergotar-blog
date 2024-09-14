<?php

use Domain\Tag\Models\Tag;
use Domain\Tag\Models\TagTranslation;

test('with-translation-scope', function () {
    $tag = Tag::factory()->hasDefaultTranslation()->create();
    $translation = $tag->translation;

    Tag::factory()->hasSupportedTranslations()->create();

    // Call the withTranslation scope.
    $tags = Tag::withTranslation()->get();
    // Check if translations are loaded.
    $tags->each(function ($tag) {
        expect($tag->relationLoaded('translation'))->toBeTrue();
        expect($tag->translation)->toBeInstanceOf(TagTranslation::class);
    });

    // Check if the tag has the right translation.
    $tag = $tags->where('id', $tag->id)->first();
    expect($tag->translation->slug)->toBe($translation->slug);
    expect($tag->translation->locale)->toBe($translation->locale);
});

test('with-translations-scope', function () {
    $locales = config('localized-routes.supported_locales');
    $tag = Tag::factory()->hasSupportedTranslations()->create();
    $translations = $tag->translations;

    // Call the withTranslations scope.
    $tags = Tag::withTranslations()->get();

    // Check if translations are loaded.
    $tags->each(function ($tag) {
        expect($tag->relationLoaded('translations'))->toBeTrue();
        $tag->translations->each(function ($translation) {
            expect($translation)->toBeInstanceOf(TagTranslation::class);
        });
    });

    // Check if the tag has the right translations.
    $tag = $tags->where('id', $tag->id)->first();
    expect($tag->translations)->toHaveCount(count($locales));
    $tag->translations->each(function ($translation) use ($translations) {
        $selectedTranslation = $translations->where('locale', $translation->locale)->first();
        expect($translation)->toBeInstanceOf(TagTranslation::class);
        expect($translation->slug)->toBe($selectedTranslation->slug);
        expect($translation->locale)->toBe($selectedTranslation->locale);
        expect($translation->name)->toBe($selectedTranslation->name);
    });
});

test('where-translation-scope', function () {
    $tag = Tag::factory()->hasDefaultTranslation()->create();
    $translation = $tag->translation;

    Tag::factory()->hasSupportedTranslations()->create();

    // Call the whereTranslation scope.
    $tag = Tag::whereTranslation('slug', $translation->slug)->first();
    // Check if the tag has the right translation.
    expect($tag->translation)->toBeInstanceOf(TagTranslation::class);
    expect($tag->translation->slug)->toBe($translation->slug);
    expect($tag->translation->locale)->toBe($translation->locale);
});
