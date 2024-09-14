<?php

use Illuminate\Support\Str;
use Domain\Blog\Models\Post;
use Domain\Category\Models\Category;
use Domain\Blog\Actions\GetPublishedPosts;

test('get-published-posts', function () {
    // Create unpublished posts with supported translations
    Post::factory(3)->unpublished()->hasSupportedTranslations()->create();

    // Create published posts with supported translations
    $count = 10;
    $categoryTitle = 'news';
    $categorySlug = Str::slug($categoryTitle);
    $category = Category::factory()->hasDefaultTranslation(['slug' => $categorySlug])->create();
    $posts = Post::factory($count)
        ->published()
        ->hasSupportedTranslations()
        ->create()
        ->each(function ($post) use ($category) {
            $post->categories()->attach($category);
        });

    // Get the published posts.
    $publishedPosts = GetPublishedPosts::run(false, $count);

    // Check if the right amount of posts are returned.
    expect($publishedPosts->count())->toBe($count);
    // Check if the right posts are returned.
    $publishedPosts->each(function ($post) use ($posts, $categorySlug) {
        expect($post->category->translation->slug)->toBe($categorySlug);

        $selectedPost = $posts->where('id', $post->id)->first();
        expect($selectedPost->is_published)->toBe(true);
        expect($selectedPost->category->translation->slug)->toBe($categorySlug);
        expect($selectedPost->translation->slug)->toBe($post->translation->slug);
        expect($selectedPost->translation->title)->toBe($post->translation->title);
    });
});

test('get-published-posts-paginated', function () {
    // Create unpublished posts with supported translations
    Post::factory(3)->unpublished()->hasSupportedTranslations()->create();
    // Create published posts with supported translations
    $posts = Post::factory(10)->published()->hasSupportedTranslations()->create();

    // Get the paginated published posts.
    $limit = 6;
    $publishedPosts = GetPublishedPosts::run(true, $limit);

    // Check if the right amount of posts are returned.
    expect($publishedPosts->count())->toBe($limit);
    // Check if the right posts are returned.
    $publishedPosts->each(function ($post) use ($posts) {
        $selectedPost = $posts->where('id', $post->id)->first();
        expect($selectedPost->is_published)->toBe(true);
        expect($selectedPost->translation->slug)->toBe($post->translation->slug);
        expect($selectedPost->translation->title)->toBe($post->translation->title);
    });
});
