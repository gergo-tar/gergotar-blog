<?php

use Domain\User\Models\User;
use Domain\Tag\Models\Tag;
use Domain\Tag\Models\TagTranslation;
use App\Filament\Resources\Tag\TagResource;
use App\Filament\Resources\Tag\TagResource\Pages\CreateTagPage;

use function Pest\Livewire\livewire;
use function Pest\Faker\fake;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('tag-create-renders', function () {
    $this->get(TagResource::getUrl('create'))->assertSuccessful();
});

test('tag-create-save', function () {
    $locales = config('localized-routes.supported_locales');
    // Create data for each locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
        ];
    }

    // Create tag trough Form.
    livewire(CreateTagPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoFormErrors();

    // Check if tag was created with the correct translations.
    foreach ($locales as $locale) {
        $this->assertDatabaseHas(TagTranslation::class, [
            'name' => $data[$locale]['name'],
            'description' => $data[$locale]['description'],
        ]);

        $translation = TagTranslation::where('name', $data[$locale]['name'])->first();
        $this->assertDatabaseHas(Tag::class, [
            'id' => $translation->tag_id,
        ]);
    }
});

test('tag-create-validation', function () {
    $defaultLocale = config('app.locale');

    // Required error for Default locale.
    livewire(CreateTagPage::class)
        ->call('create')
        ->assertHasFormErrors(["{$defaultLocale}.name" => 'required']);

    // Required error for all locales.
    $locales = config('localized-routes.supported_locales');
    $errors = [];
    foreach ($locales as $locale) {
        $data[$locale] = [
            'description' => fake()->paragraph(),
        ];
        $errors["{$locale}.name"] = $locale === $defaultLocale ? 'required' : 'required_with';
    }

    livewire(CreateTagPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors($errors);

    // Unique error.
    $tag = Tag::factory()->hasDefaultTranslation()->create();
    $data = [
        $defaultLocale => [
            'name' => $tag->translation->name,
            'description' => fake()->paragraph(),
        ],
    ];

    livewire(CreateTagPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors(["{$defaultLocale}.name" => 'unique']);
});
