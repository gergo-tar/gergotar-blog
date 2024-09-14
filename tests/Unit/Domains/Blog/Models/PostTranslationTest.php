<?php

use Illuminate\Support\Str;
use Domain\Blog\Models\Post;
use Domain\Meta\Models\Meta;
use Domain\User\Models\User;
use Domain\Blog\Models\PostTranslation;

test('get-url-attribute', function () {
    $title = 'Test Title';
    $slug = Str::slug($title);
    $translation = PostTranslation::factory()->forPost()->create([
        'title' => $title,
        'slug' => $slug,
    ]);

    expect($translation->url)->toBe(route('blog.posts.show', ['slug' => $slug]));
});

test('get-reading-time-string-attribute', function () {
    // Disable observer events.
    PostTranslation::unsetEventDispatcher();

    $translation = PostTranslation::factory()->forPost()->create([
        'reading_time' => [
            'minutes' => 1,
            'seconds' => 0,
        ],
    ]);

    expect($translation->readingTimeString)->toBe(__('Blog::post.translations.reading_time_in_minutes', [
        'min' => 1,
    ]));

    $translation = PostTranslation::factory()->forPost()->create([
        'reading_time' => [
            'minutes' => 0,
            'seconds' => 30,
        ],
    ]);

    expect($translation->readingTimeString)->toBe(__('Blog::post.translations.reading_time_in_seconds', [
        'sec' => 30,
    ]));

    $translation = PostTranslation::factory()->forPost()->create([
        'reading_time' => [
            'minutes' => 1,
            'seconds' => 31,
        ],
    ]);

    expect($translation->readingTimeString)->toBe(__('Blog::post.translations.reading_time_in_minutes', [
        'min' => 2,
    ]));
});

test('post-translation-has-created-by', function () {
    $user = User::factory()->create();
    $translation = PostTranslation::factory()->forPost()->create([
        'created_by' => $user->id,
    ]);

    expect($translation->createdBy)->toBeInstanceOf(User::class);
    expect($translation->createdBy->id)->toBe($user->id);
});

test('post-translation-has-meta', function () {
    $metas = [
        [
            'key' => 'meta_key',
            'value' => 'meta_value',
        ],
        [
            'key' => 'meta_key_2',
            'value' => 'meta_value_2',
        ],
    ];

    // Set the post translation with metas.
    $translation = PostTranslation::factory()->forPost()->create();
    foreach ($metas as $meta) {
        Meta::factory()->forMetable($translation)->create($meta);
    }
    $translation->load('metas');

    // Check if the right amount of metas are returned.
    expect($translation->relationLoaded('metas'))->toBeTrue();
    expect($translation->metas->count())->toBe(count($metas));
    // Check if the right metas are returned.
    $translation->metas->each(function ($meta, $index) use ($metas) {
        expect($meta->key)->toBe($metas[$index]['key']);
        expect($meta->value)->toBe($metas[$index]['value']);
    });
});

test('post-translation-has-post', function () {
    $post = Post::factory()->create();
    $translation = PostTranslation::factory()->forPost()->create([
        'post_id' => $post->id,
    ]);

    expect($translation->post)->toBeInstanceOf(Post::class);
    expect($translation->post->id)->toBe($post->id);
});

test('post-translation-has-updated-by', function () {
    $user = User::factory()->create();
    $translation = PostTranslation::factory()->forPost()->create([
        'updated_by' => $user->id,
    ]);

    expect($translation->updatedBy)->toBeInstanceOf(User::class);
    expect($translation->updatedBy->id)->toBe($user->id);
});
