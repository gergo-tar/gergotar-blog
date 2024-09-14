<?php

use Domain\Blog\Models\Post;
use Domain\Tag\Models\Tag;
use Domain\Translation\Actions\StoreTranslations;
use Domain\User\Models\User;
use Illuminate\Support\Arr;

test('store-translations', function () {
    $tag = Tag::factory()->create();
    $locales = config('localized-routes.supported_locales');

    // Prepare translations data.
    $data = [];
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => $locale . ' Tag Name',
        ];
    }

    // Store translations.
    $translations = StoreTranslations::run($tag, $data);

    // Check if translations are stored correctly.
    expect($translations->count())->toBe(2);
    expect($translations->pluck('locale')->toArray())->toBe($locales);
    expect($translations->pluck('name')->toArray())->toBe(Arr::pluck($data, 'name'));
});

test('store-translations-with-extra-data', function () {
    $post = Post::factory()->create();
    $locales = config('localized-routes.supported_locales');

    // Prepare translations data with metas.
    $data = [];
    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => "{$locale} Post Name",
            'content' => "{$locale} Post Content",
            'excerpt' => "{$locale} Post Excerpt",
            'meta' => [
                [
                    'key' => 'title',
                    'value' => "{$locale} Post Meta Title",
                ],
                [
                    'key' => 'description',
                    'value' => "{$locale} Post Meta Description",
                ],
            ],
        ];
    }

    // Add extra data.
    $user = User::factory()->create();
    $extra = [
        'created_by' => $user->id,
        'updated_by' => $user->id,
    ];

    // Store translations.
    $translations = StoreTranslations::run($post, $data, $extra);

    // Check if translations are stored correctly.
    expect($translations->count())->toBe(2);
    expect($translations->pluck('locale')->toArray())->toBe($locales);
    expect($translations->pluck('title')->toArray())->toBe(Arr::pluck($data, 'title'));
    expect($translations->pluck('created_by')->toArray())->toBe([$user->id, $user->id]);
    expect($translations->pluck('updated_by')->toArray())->toBe([$user->id, $user->id]);

    // Check if metas are stored correctly.
    $post->translations->each(function ($translation) use ($data) {
        expect($translation->metas->count())->toBe(2);
        expect($translation->metas->where('key', 'title')->value)->toBe($data[$translation->locale]['meta'][0]['value']);
        expect($translation->metas->where('key', 'description')->value)->toBe($data[$translation->locale]['meta'][1]['value']);
    });
});
