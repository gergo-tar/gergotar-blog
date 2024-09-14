<?php

use Domain\Content\Models\Content;
use Illuminate\Support\Facades\Cache;
use Domain\Content\Actions\GetContentBySlug;
use Domain\Content\Actions\Cache\ClearContentSlugCache;

test('clear-content-slug-cache', function () {
    // Create the content.
    $content = Content::factory()
        ->hasSupportedTranslations()
        ->create();

    // Cache the content.
    GetContentBySlug::run($content->slug);

    // Check if the content is cached.
    $cacheKey = Content::CACHE_KEY . ".{$content->slug}." . app()->getLocale();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Clear the content cache.
    ClearContentSlugCache::run($content->slug);

    // Check if the content cache is cleared.
    expect(Cache::has($cacheKey))->toBeFalse();
});
