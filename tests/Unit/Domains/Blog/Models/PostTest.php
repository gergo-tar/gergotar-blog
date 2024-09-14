<?php

use Domain\Tag\Models\Tag;
use Illuminate\Support\Str;
use Domain\Blog\Models\Post;
use Domain\Meta\Models\Meta;
use Domain\User\Models\User;
use Awcodes\Curator\Models\Media;
use Domain\Blog\Models\PostAuthor;
use Illuminate\Support\Collection;
use Domain\Category\Models\Category;
use Domain\Tag\Models\TagTranslation;
use Domain\Blog\Models\PostTranslation;
use Domain\Category\Models\CategoryTranslation;

test('post-has-post-author', function () {
    $author = PostAuthor::factory()->create();
    $post = Post::factory()->create(['author_id' => $author->id]);
    $post->loadAuthor();

    expect($post->relationLoaded('author'))->toBeTrue();
    expect($post->author)->toBeInstanceOf(PostAuthor::class);
    expect($post->author->id)->toBe($author->id);
});

test('post-has-category', function () {
    $name = 'Category Name';
    $slug = Str::slug($name);
    $post = Post::factory()
        ->hasCategoryDefaultTranslation(compact('name', 'slug'))
        ->create();
    $post->loadCategory();

    expect($post->relationLoaded('category'))->toBeTrue();
    expect($post->category)->toBeInstanceOf(Category::class);
    expect($post->category->translation->name)->toBe($name);
});

test('post-has-created-by', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['created_by' => $user->id]);

    expect($post->createdBy)->toBeInstanceOf(User::class);
    expect($post->createdBy->id)->toBe($user->id);
});

test('post-has-featured-image', function () {
    $media = Media::factory()->create();
    $post = Post::factory()->create(['featured_image_id' => $media->id]);
    $post->loadFeaturedImage();

    expect($post->relationLoaded('featuredImage'))->toBeTrue();
    expect($post->featuredImage)->toBeInstanceOf(Media::class);
    expect($post->featuredImage->id)->toBe($media->id);
    expect($post->featuredImage->name)->toBe($media->name);
});

test('post-has-meta', function () {
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
    // Set the post with metas.
    $post = Post::factory()->create();
    foreach ($metas as $meta) {
        Meta::factory()->forMetable($post)->create($meta);
    }

    $post->loadMetas();
    // Check if the right amount of metas are returned.
    expect($post->relationLoaded('metas'))->toBeTrue();
    expect($post->metas)->toBeInstanceOf(Collection::class);
    expect($post->metas->count())->toBe(count($metas));
    // Check if the right metas are returned.
    $post->metas->each(function ($meta, $key) use ($metas) {
        expect($meta->key)->toBe($metas[$key]['key']);
        expect($meta->value)->toBe($metas[$key]['value']);
    });
});

test('post-has-tags', function () {
    $tags = [
        [
            'name' => 'Tag Name',
            'slug' => 'tag-name',
        ],
        [
            'name' => 'Tag Name 2',
            'slug' => 'tag-name-2',
        ],
    ];

    // Set the post with tags.
    $post = Post::factory()->create();
    foreach ($tags as $tag) {
        $tag = Tag::factory()->hasDefaultTranslation($tag)->create();
        $post->tags()->save($tag);
    }
    $post->loadTags();

    // Check if the right amount of tags are returned.
    expect($post->relationLoaded('tags'))->toBeTrue();
    expect($post->tags)->toBeInstanceOf(Collection::class);
    expect($post->tags->count())->toBe(count($tags));
    // Check if the right tags are returned.
    $post->tags->each(function ($tag, $key) use ($tags) {
        expect($tag->relationLoaded('translation'))->toBeTrue();
        expect($tag->translation->name)->toBe($tags[$key]['name']);
        expect($tag->translation->slug)->toBe($tags[$key]['slug']);
    });
});

test('post-has-translations', function () {
    $locales = config('localized-routes.supported_locales');
    $post = Post::factory()->hasSupportedTranslations()->create();
    $post->loadTranslations();

    // Check if the post has the right amount of translations.
    expect($post->relationLoaded('translations'))->toBeTrue();
    expect($post->translations->count())->toBe(count($locales));
    // Check if the translations are instances of the PostTranslation model.
    $post->translations->each(function ($translation) use ($locales) {
        expect($locales)->toContain($translation->locale);
        expect($translation)->toBeInstanceOf(PostTranslation::class);
    });
});

test('post-has-updated-by', function () {
    $user = User::factory()->create();
    $post = Post::factory()->create(['updated_by' => $user->id]);

    expect($post->updatedBy)->toBeInstanceOf(User::class);
    expect($post->updatedBy->id)->toBe($user->id);
});

test('load-relationships', function () {
    $post = Post::factory()
        ->hasDefaultTranslation()
        ->hasCategoryDefaultTranslation()
        ->hasFeaturedImage()
        ->hasTagDefaultTranslation()
        ->create();

    $post->loadRelationships();

    // Check if the right relationships are loaded.
    expect($post->relationLoaded('author'))->toBeTrue();
    expect($post->author)->toBeInstanceOf(PostAuthor::class);

    expect($post->relationLoaded('category'))->toBeTrue();
    expect($post->category)->toBeInstanceOf(Category::class);
    expect($post->category->relationLoaded('translation'))->toBeTrue();
    expect($post->category->translation)->toBeInstanceOf(CategoryTranslation::class);

    expect($post->relationLoaded('featuredImage'))->toBeTrue();
    expect($post->featuredImage)->toBeInstanceOf(Media::class);

    expect($post->relationLoaded('tags'))->toBeTrue();
    expect($post->tags)->toBeInstanceOf(Collection::class);
    expect($post->tags->first()->relationLoaded('translation'))->toBeTrue();
    expect($post->tags->first()->translation)->toBeInstanceOf(TagTranslation::class);

    expect($post->relationLoaded('translation'))->toBeTrue();
    expect($post->translation)->toBeInstanceOf(PostTranslation::class);
});

test('load-translation-metas-relationships', function () {
    $post = Post::factory()
        ->hasSupportedTranslationsWithMetas()
        ->create();

    $post->loadTranslationsMetas();

    // Check if the right relationships are loaded.
    $post->translations->each(function ($translation) {
        expect($translation->relationLoaded('metas'))->toBeTrue();
        $translation->metas->each(function ($meta) {
            expect($meta)->toBeInstanceOf(Meta::class);
        });
    });
});

test('get-featured-image-alt-attribute', function () {
    // Media with alt text.
    $alt = 'Alt Text';
    $post = Post::factory()
        ->hasFeaturedImage(compact('alt'))
        ->create();
    // Test if the alt text is returned.
    expect($post->featuredImageAlt)->toBe($alt);

    // Media without alt text.
    $post = Post::factory()
        ->hasDefaultTranslation()
        ->hasFeaturedImage(['alt' => null])
        ->create();
    $title = $post->translation->title;
    // Test if the title is returned.
    expect($post->featuredImageAlt)->toBe($title);
});

test('get-featured-image-url-attribute', function () {
    // Media with URL.
    $post = Post::factory()->hasFeaturedImage()->create();
    $url = $post->featuredImage->url;
    // Test if the URL is returned.
    expect($post->featuredImageUrl)->toBe($url);

    // Media without URL.
    $post = Post::factory()->create();
    expect($post->featuredImageUrl)->toBeNull();
});

test('get-published-at-formatted-attribute', function () {
    $date = now();
    $post = Post::factory()->create(['published_at' => $date]);
    $formatted = $post->publishedAtFormatted;
    // Test if the formatted date is returned.
    expect($formatted)->toBe($post->published_at->format('l, F j, Y'));

    // Change to HU locale.
    app()->setLocale('hu');
    $formatted = $post->publishedAtFormatted;
    // Test if the formatted date is returned.
    expect($formatted)->toBe($post->published_at->translatedFormat('Y. F j., l'));
});

test('get-tags-names-attribute', function () {
    $tags = [
        [
            'name' => 'Tag Name',
            'slug' => 'tag-name',
        ],
        [
            'name' => 'Tag Name 2',
            'slug' => 'tag-name-2',
        ],
    ];
    // Set the post with tags.
    $post = Post::factory()->create();
    foreach ($tags as $tag) {
        $tag = Tag::factory()->hasDefaultTranslation($tag)->create();
        $post->tags()->save($tag);
    }
    // Test if the tags names are returned.
    expect($post->tagsNames)->toBe($post->tags->pluck('translation.name')->toArray());
});
