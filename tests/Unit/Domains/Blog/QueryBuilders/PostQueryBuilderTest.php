<?php

use Domain\Blog\Models\Post;
use Domain\Meta\Models\Meta;
use Domain\Blog\Models\PostTranslation;

test('order-by-pulished-at-scope', function () {
    // Create posts with different published_at dates.
    $post = Post::factory()->create(['published_at' => now()->subDays(3)]);
    Post::factory()->create(['published_at' => now()->subDays(2)]);
    $post3 = Post::factory()->create(['published_at' => now()->subDays(1)]);

    // Get posts ordered by published_at.
    $posts = Post::orderByPublishedAt()->get();

    // Check if the first post is the last created post.
    expect($posts->first()->id)->toBe($post3->id);
    expect($posts->last()->id)->toBe($post->id);
});

test('where-published-scope', function () {
    // Create a published and an unpublished post.
    $post = Post::factory()->published()->create();
    Post::factory()->unpublished()->create();

    // Get published posts.
    $posts = Post::wherePublished()->get();

    // Check if only the published post is returned and matches the created one.
    expect($posts->count())->toBe(1);
    expect($posts->first()->id)->toBe($post->id);
});

test('where-unpublished-scope', function () {
    // Create a published and an unpublished post.
    Post::factory()->published()->create();
    $post = Post::factory()->unpublished()->create();

    // Get unpublished posts.
    $posts = Post::whereUnpublished()->get();

    // Check if only the unpublished post is returned and matches the created one.
    expect($posts->count())->toBe(1);
    expect($posts->first()->id)->toBe($post->id);
});

test('where-translation-slug-scope', function () {
    $post = Post::factory()->hasDefaultTranslation()->create();
    $translation = $post->translation;

    $post2 = Post::factory()->hasDefaultTranslation()->create();
    $translation2 = $post2->translation;

    $post = Post::whereTranslationSlug($translation->slug)->first();
    expect($post->id)->toBe($post->id);
    expect($post->translation->slug)->toBe($translation->slug);

    $post2 = Post::whereTranslationSlug($translation2->slug)->first();
    expect($post2->id)->toBe($post2->id);
    expect($post2->translation->slug)->toBe($translation2->slug);
});

test('with-category-scope', function () {
    $post = Post::factory()
        ->hasCategoryDefaultTranslation()
        ->create();
    $category1 = $post->category;

    $post2 = Post::factory()
        ->hasCategoryDefaultTranslation()
        ->create();
    $category2 = $post2->category;

    // Get posts with category.
    $posts = Post::withCategory()->whereIn('id', [$post->id, $post2->id])->get();

    // Check if the categories are loaded.
    $posts->each(function ($post, $key) use ($category1, $category2) {
        expect($post->relationLoaded('category'))->toBeTrue();
        $selectedCategory = $post->category;
        expect($selectedCategory->id)->toBe($key === 0 ? $category1->id : $category2->id);
        expect($selectedCategory->relationLoaded('translation'))->toBeTrue();
        expect($selectedCategory->translation->name)
            ->toBe($key === 0 ? $category1->translation->name : $category2->translation->name);
    });
});

test('with-featured-image-scope', function () {
    $post = Post::factory()->hasFeaturedImage()->create();
    $media = $post->featuredImage;
    $post2 = Post::factory()->create();

    // Get posts with featured image.
    $posts = Post::withFeaturedImage()->get();
    // Check if the featured image is loaded.
    $post = $posts->where('id', $post->id)->first();
    expect($post->relationLoaded('featuredImage'))->toBeTrue();
    expect($post->featuredImage->id)->toBe($media->id);
    expect($post->featuredImage->url)->toBe($media->url);
    // Check if the post without featured image has null featured image.
    $post2 = $posts->where('id', $post2->id)->first();
    expect($post2->relationLoaded('featuredImage'))->toBeTrue();
    expect($post2->featuredImage)->toBeNull();
});

test('with-translation-scope', function () {
    $post = Post::factory()->hasDefaultTranslation()->create();
    $translation = $post->translation;

    Post::factory()->hasSupportedTranslations()->create();

    // Call the withTranslation scope.
    $posts = Post::withTranslation()->get();
    // Check if translations are loaded.
    $posts->each(function ($post) {
        expect($post->relationLoaded('translation'))->toBeTrue();
        expect($post->translation)->toBeInstanceOf(PostTranslation::class);
    });

    // Check if the post has the right translation.
    $post = $posts->where('id', $post->id)->first();
    expect($post->translation->slug)->toBe($translation->slug);
    expect($post->translation->locale)->toBe($translation->locale);
    expect($post->translation->title)->toBe($translation->title);
    expect($post->translation->content)->toBe($translation->content);
    expect($post->translation->excerpt)->toBe($translation->excerpt);
});

test('with-translations-scope', function () {
    $locales = config('localized-routes.supported_locales');
    $post = Post::factory()->hasSupportedTranslations()->create();
    $translations = $post->translations;

    // Call the withTranslations scope.
    $posts = Post::withTranslations()->get();

    // Check if translations are loaded.
    $posts->each(function ($post) {
        expect($post->relationLoaded('translations'))->toBeTrue();
        $post->translations->each(function ($translation) {
            expect($translation)->toBeInstanceOf(PostTranslation::class);
        });
    });

    // Check if the post has the right translations.
    $post = $posts->where('id', $post->id)->first();
    expect($post->translations)->toHaveCount(count($locales));
    $post->translations->each(function ($translation) use ($translations) {
        $selectedTranslation = $translations->where('locale', $translation->locale)->first();
        expect($translation)->toBeInstanceOf(PostTranslation::class);
        expect($translation->slug)->toBe($selectedTranslation->slug);
        expect($translation->locale)->toBe($selectedTranslation->locale);
        expect($translation->title)->toBe($selectedTranslation->title);
    });
});

test('with-translations-meta-scope', function () {
    $count = 2;
    $post = Post::factory()
        ->hasSupportedTranslationsWithMetas($count)
        ->create();

    // Call the withTranslations scope.
    $post = Post::withTranslationsMetas()->where('id', $post->id)->first();

    // Check if the post has the right translations.
    expect($post->relationLoaded('translations'))->toBeTrue();
    $translations = $post->translations;
    $post->translations->each(function ($translation) use ($count, $translations) {
        $selectedTranslation = $translations->where('locale', $translation->locale)->first();
        expect($translation->relationLoaded('metas'))->toBeTrue();
        expect($translation->metas)->toHaveCount($count);
        $translation->metas->each(function ($meta) use ($selectedTranslation) {
            $selectedMeta = $selectedTranslation->metas->where('key', $meta->key)->first();
            expect($meta)->toBeinstanceOf(Meta::class);
            expect($meta->value)->toBe($selectedMeta->value);
        });
    });
});
