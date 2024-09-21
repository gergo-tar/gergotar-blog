<?php

use Domain\Tag\Models\Tag;
use Domain\Blog\Models\Post;
use Domain\User\Models\User;
use Domain\Blog\Actions\SavePost;
use Domain\Blog\Models\PostAuthor;
use Domain\Category\Models\Category;
use Illuminate\Support\Facades\Cache;
use Domain\Blog\Actions\EstimatePostReadingTime;

beforeEach(function () {
    $this->actingAs(User::factory()->create());
});

test('create-post', function () {
    $author = PostAuthor::factory()->create();
    $category = Category::factory()->create();
    $tags = Tag::factory()->count(3)->create();
    $data = [
        'author_id' => $author->id,
        'category_id' => $category->id,
        'is_published' => true,
        'tags' => $tags->pluck('id')->toArray(),
    ];

    // Set post data for each supported locale.
    $locales = config('localized-routes.supported_locales');
    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => "Post Title {$locale}",
            'content' => "<p>Post Content {$locale}</p>" .
                '<p><img src="https://example.com/image.jpg" alt="Image Alt" width="100" height="100"></p>' .
                '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>' .
                '<p>Nullam nec purus nec nunc ultricies aliquam.</p>' .
                '<p>Donec nec nunc nec nunc ultricies aliquam.</p>',

        ];
    }

    $post = SavePost::run($data);

    // Check if the post is created correctly.
    expect($post)->toBeInstanceOf(Post::class);
    expect($post->is_published)->toBe(true);
    // Check if post has the right number of translations.
    expect($post->translations)->toHaveCount(count($locales));
    // Check if the post translations are created correctly.
    foreach ($locales as $locale) {
        $translation = $post->translations->where('locale', $locale)->first();
        expect($translation->title)->toBe($data[$locale]['title']);
        expect($translation->content)->toBe($data[$locale]['content']);
        // Check Metas.
        expect($translation->metas->where('key', 'title')->first()->value)->toBe($data[$locale]['title']);
        expect($translation->metas->where('key', 'description')->first()->value)
            // Max length of description meta is 160 characters.
            ->toBe(substr(strip_tags($data[$locale]['content']), 0, 160));
    }

    // Check if the reading time is estimated correctly.
    $readingTime = EstimatePostReadingTime::run($data[config('app.locale')]['content']);
    // Turn float values in readingTime array to integers.
    $readingTime = array_map(fn ($time) => (int) $time, $readingTime);
    expect($post->translation->reading_time)->toBe($readingTime);

    // Check if the post has the right author.
    expect($post->author->id)->toBe($author->id);
    // Check if the post has the right category.
    expect($post->category->id)->toBe($category->id);
    // Check if the post has the right tags.
    expect($post->tags->pluck('id')->toArray())->toBe($tags->pluck('id')->toArray());
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
        'is_published' => false,
        'tags' => $tags->pluck('id')->toArray(),
    ];

    // Set post data for each supported locale.
    $locales = config('localized-routes.supported_locales');
    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => "Post Title {$locale}",
            'content' => "Post Content {$locale}",
            'meta' => [
                'title' => "Post Title Meta {$locale}",
                'description' => "Post Description Meta {$locale}",
            ],
        ];
    }

    $post = SavePost::run($data, $post);

    // Check if the post is updated correctly.
    expect($post)->toBeInstanceOf(Post::class);
    expect($post->is_published)->toBe(false);
    // Check if post has the right number of translations.
    expect($post->translations)->toHaveCount(count($locales));
    // Check if the post translations are updated correctly.
    foreach ($locales as $locale) {
        $translation = $post->translations->where('locale', $locale)->first();
        expect($translation->title)->toBe("Post Title {$locale}");
        expect($translation->content)->toBe("<p>Post Content {$locale}</p>");
        // Check Metas.
        expect($translation->metas->where('key', 'title')->first()->value)->toBe("Post Title Meta {$locale}");
        expect($translation->metas->where('key', 'description')->first()->value)->toBe("Post Description Meta {$locale}");
    }

    // Check if the post has the right category.
    expect($post->category->id)->toBe($category->id);
    // Check if the post has the right tags.
    expect($post->tags->pluck('id')->toArray())->toBe($tags->pluck('id')->toArray());
});

test('update-post-cache-cleared', function () {
    $post = Post::factory()
        ->published()
        ->hasSupportedTranslationsWithMetas()
        ->hasCategoryDefaultTranslation()
        ->hasFeaturedImage()
        ->hasTagDefaultTranslation()
        ->create();

    $data = [
        'is_published' => false,
    ];

    $cacheKey = Post::CACHE_KEY . "-$post->translation->slug";
    $cache = Cache::store();
    $ttl = config('cache.ttl');

    // Check if the current cache driver supports tags.
    if (method_exists($cache->getStore(), 'tags')) {
        // If it does, use tags to cache the post.
        Cache::tags(Post::CACHE_KEY)
            ->remember($cacheKey, $ttl, function () use ($post) {
                return $post;
            });

        SavePost::run($data, $post);
        // Check if the cache for the post is cleared.
        expect(cache()->tags(Post::CACHE_KEY)->has($cacheKey))->toBeFalse();
        return;
    }

    // If it doesn't, cache the post without tags.
    Cache::remember($cacheKey, $ttl, function () use ($post) {
        return $post;
    });

    SavePost::run($data, $post);

    // Check if the cache for the post is cleared.
    expect(cache()->has($cacheKey))->toBeFalse();
});
