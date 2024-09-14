<?php

use Domain\Tag\Models\Tag;
use Domain\Blog\Models\Post;
use Domain\User\Models\User;
use Domain\Category\Models\Category;
use Domain\Blog\Actions\SyncPostRelationships;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('update-post', function () {
    $category = Category::factory()->create();
    $tags = Tag::factory()->count(3)->create();
    $post = Post::factory()
        ->published()
        ->hasSupportedTranslationsWithMetas()
        ->hasCategoryDefaultTranslation()
        ->hasFeaturedImage()
        ->hasTagDefaultTranslation()
        ->create();

    $data = [
        'category_id' => $category->id,
        'tags' => $tags->pluck('id')->toArray(),
    ];

    SyncPostRelationships::run($post, $data);

    $post->refresh();
    // Check if the post has the right category.
    expect($post->category->id)->toBe($category->id);
    // Check if the post has the right tags.
    expect($post->tags->pluck('id')->toArray())->toBe($tags->pluck('id')->toArray());
});
