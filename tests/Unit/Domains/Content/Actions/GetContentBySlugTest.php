<?php

use Illuminate\Support\Str;
use Domain\Content\Models\Content;
use Illuminate\Support\Facades\Cache;
use Domain\Content\Actions\GetContentBySlug;

test('get-content-by-slug', function () {
    // Create contents.
    Content::factory(3)
        ->hasSupportedTranslations()
        ->create();

    // Create the selected content.
    $title = 'My first content';
    $slug = Str::slug($title);

    $content = Content::factory()
        ->hasSupportedTranslationsWithMetas()
        ->create(compact('title', 'slug'));
    $translation = $content->translation;

    // Get the published content by slug.
    $publishedContent = GetContentBySlug::run($slug);

    // Check if the right content is returned.
    expect($publishedContent->id)->toBe($content->id);
    expect($publishedContent->slug)->toBe($slug);
    expect($publishedContent->title)->toBe($title);
    expect($publishedContent->translation->content)->toBe($translation->content);
    // Check meta data.
    expect($publishedContent->translation->metas->count())->toBe($translation->metas->count());
    $publishedContent->translation->metas->each(function ($meta) use ($translation) {
        expect($translation->metas->pluck('key'))->toContain($meta->key);
        expect($translation->metas->pluck('value'))->toContain($meta->value);
    });

    // Check if the content is cached.
    $cacheKey = Content::CACHE_KEY . ".{$slug}." . app()->getLocale();
    expect(Cache::has($cacheKey))->toBeTrue();
    // Check if the content is cached with the right value.
    expect(Cache::get($cacheKey)->id)->toBe($content->id);
});

test('get-content-by-slug-not-found', function () {
    // Create contents.
    Content::factory(3)
        ->hasSupportedTranslations()
        ->create();

    // Get the published content by slug.
    $publishedContent = GetContentBySlug::run('not-found');

    // Check if no content is returned.
    expect($publishedContent)->toBeNull();
});
