<?php

use Illuminate\Support\Str;
use Domain\Blog\Models\Post;
use Domain\Blog\Actions\GetPublishedPostBySlug;

test('get-published-post-by-slug', function () {
    // Create unpublished posts.
    Post::factory(3)
        ->unpublished()
        ->hasSupportedTranslations()
        ->create();
    // Create published posts.
    $post = Post::factory(3)
        ->published()
        ->hasSupportedTranslations()
        ->create();

    // Create the selected post.
    $title = 'My first post';
    $slug = Str::slug($title);

    $post = Post::factory()
        ->published()
        ->hasDefaultTranslation(compact('title', 'slug'))
        ->create();
    $translation = $post->translation;

    // Get the published post by slug.
    $publishedPost = GetPublishedPostBySlug::run($slug);

    // Check if the right post is returned.
    expect($publishedPost->id)->toBe($post->id);
    expect($publishedPost->translation->slug)->toBe($slug);
    expect($publishedPost->translation->title)->toBe($title);
    expect($publishedPost->translation->content)->toBe($translation->content);
    // Check meta data.
    expect($publishedPost->translation->metas->count())->toBe($translation->metas->count());
    $publishedPost->translation->metas->each(function ($meta) use ($translation) {
        expect($translation->metas->pluck('key'))->toContain($meta->key);
        expect($translation->metas->pluck('value'))->toContain($meta->value);
    });
});

test('get-published-post-by-slug-not-found', function () {
    // Create unpublished posts.
    Post::factory(3)
        ->unpublished()
        ->hasSupportedTranslations()
        ->create();
    // Create published posts.
    Post::factory(3)
        ->published()
        ->hasSupportedTranslations()
        ->create();

    // Get the published post by slug.
    $publishedPost = GetPublishedPostBySlug::run('not-found');

    // Check if no post is returned.
    expect($publishedPost)->toBeNull();
});
