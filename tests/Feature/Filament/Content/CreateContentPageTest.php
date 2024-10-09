<?php

use Domain\Meta\Models\Meta;
use Domain\User\Models\User;
use Domain\Content\Models\Content;
use Domain\Content\Models\ContentTranslation;
use App\Filament\Resources\Content\ContentResource;
use App\Filament\Resources\Content\ContentResource\Pages\CreateContentPage;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('content-create-renders', function () {
    $this->get(ContentResource::getUrl('create'))->assertSuccessful();
});

test('content-create-save', function () {
    $locales = config('localized-routes.supported_locales');
    $data = [
        'title' => fake()->unique()->word(),
    ];

    // Create data for each locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'content' => '<p>' . fake()->paragraph() . '</p>',
            'meta' => [
                'title' => fake()->unique()->word(),
                'description' => fake()->paragraph(1),
            ],
        ];
    }

    // Create content trough Form.
    livewire(CreateContentPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoFormErrors();

    // Check if content was created.
    $this->assertDatabaseHas(Content::class, [
        'title' => $data['title'],
    ]);
    $content = Content::where('title', $data['title'])->first();

    // Check if content was created with the correct translations.
    foreach ($locales as $locale) {
        $this->assertDatabaseHas(ContentTranslation::class, [
            'content_id' => $content->id,
        ]);
        $translation = ContentTranslation::where('content_id', $content->id)
            ->where('locale', $locale)
            ->first();
        expect(tiptap_converter()->asHtml($translation->content))->toBe($data[$locale]['content']);
        // Check if content meta was created with the correct translations.
        $this->assertDatabaseHas(Meta::class, [
            'key' => 'title',
            'value' => $data[$locale]['meta']['title'],
            'metable_id' => $translation->id,
            'metable_type' => ContentTranslation::class,
        ]);
        $this->assertDatabaseHas(Meta::class, [
            'key' => 'description',
            'value' => $data[$locale]['meta']['description'],
            'metable_id' => $translation->id,
            'metable_type' => ContentTranslation::class,
        ]);
    }
});

test('content-create-validation', function () {
    $defaultLocale = config('app.locale');

    // Required error for Default locale.
    livewire(CreateContentPage::class)
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'required',
            "{$defaultLocale}.content" => 'required'
        ]);

    // Unique error.
    $content = Content::factory()->hasDefaultTranslation()->create();
    $data = [
        'title' => $content->title,
        $defaultLocale => [
            'content' => '<p>' . fake()->paragraph() . '</p>',
        ],
    ];

    livewire(CreateContentPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors([
            'title' => 'unique',
        ]);
});
