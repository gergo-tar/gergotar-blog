<?php

use Domain\Tag\Models\Tag;
use Domain\Blog\Models\Post;
use Domain\Tag\Models\TagTranslation;

test('tag-has-posts', function () {
    $count = 3;
    $tag = Tag::factory()->hasPosts($count)->create();

    // Check if the tag has the right amount of posts.
    expect($tag->posts->count())->toBe($count);
    // Check if the posts are instances of the Post model.
    $tag->posts->each(function ($post) {
        expect($post)->toBeInstanceOf(Post::class);
    });
});

test('tag-has-translations', function () {
    $locales = config('localized-routes.supported_locales');
    $tag = Tag::factory()->hasSupportedTranslations()->create();

    // Check if the tag has the right amount of translations.
    expect($tag->translations->count())->toBe(count($locales));
    // Check if the translations are instances of the TagTranslation model.
    $tag->translations->each(function ($translation) use ($locales) {
        expect($locales)->toContain($translation->locale);
        expect($translation)->toBeInstanceOf(TagTranslation::class);
    });
});
