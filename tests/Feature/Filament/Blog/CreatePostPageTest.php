<?php

use Domain\Tag\Models\Tag;
use Domain\Blog\Models\Post;
use Domain\Meta\Models\Meta;
use Domain\User\Models\User;
use Domain\Tag\Models\Taggable;
use Awcodes\Curator\Models\Media;
use Domain\Blog\Models\PostAuthor;
use Domain\Category\Models\Category;
use Domain\Blog\Models\PostTranslation;
use Domain\Category\Models\Categoriable;
use App\Filament\Resources\Blog\PostResource;
use App\Filament\Resources\Blog\PostResource\Pages\CreatePostPage;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('post-create-renders', function () {
    $this->get(PostResource::getUrl('create'))->assertSuccessful();
});

test('post-create-save', function () {
    $author = PostAuthor::factory()->create();
    $category = Category::factory()->hasDefaultTranslation()->create();
    $tags = Tag::factory(3)->hasDefaultTranslation()->create();
    // TODO: Add featured image. Plugin is not working properly.
    // $media = Media::factory()->create();
    $data = [
        'author_id' => $author->id,
        'category_id' => $category->id,
        'is_published' => true,
        'tags' => $tags->pluck('id')->toArray(),
        // 'featured_image_id' => $media->id,
    ];

    $locales = config('localized-routes.supported_locales');
    // Create data for each locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => "Post Title {$locale}",
            'content' => "Post Content {$locale}",
        ];
    }

    // Create post trough Form.
    livewire(CreatePostPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoFormErrors();

    // Check if post was created with the correct translations.
    foreach ($locales as $locale) {
        $this->assertDatabaseHas(PostTranslation::class, [
            'locale' => $locale,
            'title' => $data[$locale]['title'],
            'content' => json_encode([
                'type' => 'doc',
                'content' => [
                    [
                        'type' => 'paragraph',
                        'attrs' => ['textAlign' => 'start'],
                        'content' => [
                            [
                                'type' => 'text',
                                'text' => $data[$locale]['content'],
                            ],
                        ],
                    ],
                ],
            ]),
        ]);

        $translation = PostTranslation::where('title', $data[$locale]['title'])->first();

        // Check if the post translation has the right metas.
        $this->assertDatabaseHas(Meta::class, [
            'metable_id' => $translation->id,
            'metable_type' => PostTranslation::class,
            'key' => 'title',
            'value' => $data[$locale]['title'],
        ]);
        $this->assertDatabaseHas(Meta::class, [
            'metable_id' => $translation->id,
            'metable_type' => PostTranslation::class,
            'key' => 'description',
            'value' => $data[$locale]['content'],
        ]);
    }

    // Check if the post has the right data.
    $this->assertDatabaseHas(Post::class, [
        'id' => $translation->post_id,
        'published_at' => $data['is_published'] ? now() : null,
        'is_published' => (int) $data['is_published'],
        'author_id' => $data['author_id'],
        // 'featured_image_id' => $data['featured_image_id'],
    ]);

    // Check if the post has the right category.
    $this->assertDatabaseHas(Categoriable::class, [
        'categoriable_id' => $translation->post_id,
        'categoriable_type' => Post::class,
        'category_id' => $data['category_id'],
    ]);

    // Check if the post has the right tags.
    foreach ($data['tags'] as $tagId) {
        $this->assertDatabaseHas(Taggable::class, [
            'tag_id' => $tagId,
            'taggable_id' => $translation->post_id,
            'taggable_type' => Post::class,
        ]);
    }
});

test('post-create-validation', function () {
    $defaultLocale = config('app.locale');

    // Required error for Default locale.
    livewire(CreatePostPage::class)
        ->call('create')
        ->assertHasFormErrors([
            'category_id' => 'required',
            "{$defaultLocale}.title" => 'required',
            "{$defaultLocale}.content" => 'required',
        ]);

    // Required error for all locales.
    $locales = config('localized-routes.supported_locales');
    $errors = [];
    foreach ($locales as $locale) {
        $data[$locale] = [
            'content' => fake()->paragraph(),
        ];
        $errors["{$locale}.title"] = $locale === $defaultLocale ? 'required' : 'required_with';
    }

    livewire(CreatePostPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors($errors);

    // Unique error.
    $post = Post::factory()->hasDefaultTranslation(['title' => 'Unique Post Title'])->create();
    $data = [
        $defaultLocale => [
            'title' => $post->translation->title,
            'content' => fake()->paragraph(),
        ],
    ];

    livewire(CreatePostPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors(["{$defaultLocale}.title" => 'unique']);
});
