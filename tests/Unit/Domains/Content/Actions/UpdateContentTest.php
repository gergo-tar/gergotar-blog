<?php

use Domain\Content\Models\Content;
use Illuminate\Support\Facades\Cache;
use Domain\Content\Actions\UpdateContent;
use Domain\Content\Actions\GetContentBySlug;

test('update-content', function () {
    // Create the content.
    $content = Content::factory()
        ->hasSupportedTranslations()
        ->create();

    // Cache the content.
    GetContentBySlug::run($content->slug);

    // Check if the content is cached.
    $cacheKey = Content::CACHE_KEY . ".{$content->slug}." . app()->getLocale();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update the content.
    $title = 'Updated Content Title';
    $data = compact('title');

    $locales = config('localized-routes.supported_locales');
    // Set category data for each supported locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'content' => fake()->paragraph,
        ];
    }

    $updatedContent = UpdateContent::run($content, $data);

    expect($updatedContent)->toBeInstanceOf(Content::class);
    expect($updatedContent->title)->toBe($title);
    expect($updatedContent->translations)->toHaveCount(count($locales));
    foreach ($locales as $locale) {
        $translation = $updatedContent->translations->where('locale', $locale)->first();
        expect($translation->content)->toBe($data[$locale]['content']);
    }

    // Check if the content cache is cleared.
    expect(Cache::has($cacheKey))->toBeFalse();
});
