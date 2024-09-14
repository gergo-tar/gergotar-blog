<?php

use Domain\Tag\Models\Tag;
use Domain\Blog\Models\Post;
use Domain\Meta\Models\Meta;
use Domain\User\Models\User;
use Filament\Actions\DeleteAction;
use Domain\Category\Models\Category;
use App\Filament\Resources\Blog\PostResource;
use App\Filament\Resources\Blog\PostResource\Pages\EditPostPage;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('post-edit-renders', function () {
    $this->get(
        PostResource::getUrl(
            'edit',
            ['record' => Post::factory()->create()]
        )
    )->assertSuccessful();
});

test('post-edit-data', function () {
    $post = Post::factory()
        ->hasSupportedTranslationsWithMetas(1, ['key' => 'title'])
        ->hasCategoryDefaultTranslation()
        ->hasTagDefaultTranslation()
        ->published()
        ->create();

    $data = [
        'category_id' => $post->category->id,
        'tags' => $post->tags->pluck('id')->toArray(),
        'author_id' => $post->author->id,
        'published_at' => $post->published_at->format('Y-m-d H:i:s'),
        'is_published' => true,
    ];

    foreach ($post->translations as $translation) {
        $data[$translation->locale] = [
            'title' => $translation->title,
            // TODO: TipTap Editor modifies the content. Need to find a way to compare it.
            // 'content' => "<p>{$translation->content}</p>",
            'meta' => [
                'title' => $translation->metas->where('key', 'title')->first()->value,
                'description' => '',
            ],
        ];
    }

    // Check if form is filled with the correct data.
    livewire(EditPostPage::class, ['record' => $post->getRouteKey()])
        ->assertFormSet($data);
});

test('post-edit-save', function () {
    $post = Post::factory()
        ->hasSupportedTranslationsWithMetas(1, ['key' => 'title'])
        ->hasCategoryDefaultTranslation()
        ->hasTagDefaultTranslation()
        ->create();

    $category = Category::factory()->hasDefaultTranslation()->create();
    $tags = Tag::factory(3)->hasDefaultTranslation()->create();

    $data = [
        'category_id' => $category->id,
        'tags' => $tags->pluck('id')->toArray(),
    ];
    foreach ($post->translations as $translation) {
        $data[$translation->locale] = [
            'title' => fake()->unique()->word(),
            'content' => fake()->paragraph(),
            // 'meta' => [
            //     'title' => fake()->sentence(1),
            // ],
        ];
    }

    livewire(EditPostPage::class, ['record' => $post->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasNoFormErrors();

    // Check if post was updated with the correct translations.
    $post->refresh()->load('translations')->translations->each(function ($translation) use ($data) {
        expect($translation->title)->toBe($data[$translation->locale]['title']);
        expect($translation->content)->toBe("<p>{$data[$translation->locale]['content']}</p>");

        $translation->load('metas');

        // TODO: check why this is not working.
        // Check Metas.
        // expect($translation->metas->where('key', 'title')->first()->value)
        //     ->toBe($data[$translation->locale]['meta']['title']);
    });

    // Check if the post has the right data.
    expect($post->category->id)->toBe($category->id);
    expect($post->tags->pluck('id')->toArray())->toBe($tags->pluck('id')->toArray());
});

test('post-edit-validation', function () {
    $defaultLocale = config('app.locale');
    $post = Post::factory()->hasSupportedTranslations()->create();

    // Required error for Default locale.
    livewire(EditPostPage::class, ['record' => $post->getRouteKey()])
        ->fillForm([
            "{$defaultLocale}.title" => null,
        ])
        ->call('save')
        ->assertHasFormErrors(["{$defaultLocale}.title" => 'required']);

    // Required error for all locales.
    $locales = config('localized-routes.supported_locales');
    $errors = [];
    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => null,
            'content' => fake()->paragraph(),
        ];
        $errors["{$locale}.title"] = $locale === $defaultLocale ? 'required' : 'required_with';
    }

    livewire(EditPostPage::class, ['record' => $post->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors($errors);

    // Unique error.
    $data = [
        'category_id' => Category::factory()->hasDefaultTranslation()->create()->id,
        $defaultLocale => [
            'title' => $post->translation->title,
        ],
    ];
    $otherPost = Post::factory()->hasDefaultTranslation()->create();

    livewire(EditPostPage::class, ['record' => $otherPost->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors(["{$defaultLocale}.title" => 'unique']);
});

test('post-delete', function () {
    $post = Post::factory()->create();

    livewire(EditPostPage::class, [
        'record' => $post->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($post);
});
