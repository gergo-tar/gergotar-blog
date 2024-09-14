<?php

use Domain\Blog\Models\Post;
use Domain\Blog\Models\PostTranslation;

test('post-renders-with-meta', function () {
    $post = Post::factory()
        ->published()
        ->hasDefaultTranslation()
        ->hasCategoryDefaultTranslation()
        ->hasTagDefaultTranslation()
        ->create();

    $title = 'Meta title';
    $description = 'Meta description';
    $post->translation->metas()->createMany([
        [
            'key' => 'title',
            'value' => $title,
            'metable_type' => PostTranslation::class,
        ],
        [
            'key' => 'description',
            'value' => $description,
            'metable_type' => PostTranslation::class,
        ]
    ]);

    $blogAuthor = get_blog_owner_name(app()->getLocale());
    $this->get(route('blog.posts.show', ['slug' => $post->translation->slug]))
        ->assertOk()
        ->assertSee("<title>{$title} | {$blogAuthor}</title>", false)
        ->assertSee("<meta name=\"description\" content=\"{$description}\">", false);
});
