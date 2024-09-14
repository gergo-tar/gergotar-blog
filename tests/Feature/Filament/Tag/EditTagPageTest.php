<?php

use Domain\User\Models\User;
use Filament\Actions\DeleteAction;
use Domain\Tag\Models\Tag;
use App\Filament\Resources\Tag\TagResource;
use App\Filament\Resources\Tag\TagResource\Pages\EditTagPage;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('tag-edit-renders', function () {
    $this->get(
        TagResource::getUrl(
            'edit',
            ['record' => Tag::factory()->create()]
        )
    )->assertSuccessful();
});

test('tag-edit-data', function () {
    $tag = Tag::factory()->hasSupportedTranslations()->create();

    $data = [];
    foreach ($tag->translations as $translation) {
        $data[$translation->locale] = [
            'name' => $translation->name,
            'description' => $translation->description,
        ];
    }

    // Check if form is filled with the correct data.
    livewire(EditTagPage::class, ['record' => $tag->getRouteKey()])
        ->assertFormSet($data);
});

test('tag-edit-save', function () {
    $tag = Tag::factory()->hasSupportedTranslations()->create();

    $data = [];
    foreach ($tag->translations as $translation) {
        $data[$translation->locale] = [
            'name' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
        ];
    }

    livewire(EditTagPage::class, ['record' => $tag->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasNoFormErrors();

    // Check if tag was updated with the correct translations.
    $tag->refresh()->translations->each(function ($translation) use ($data) {
        expect($data[$translation->locale])->toBe([
            'name' => $translation->name,
            'description' => $translation->description,
        ]);
    });
});

test('tag-edit-validation', function () {
    $defaultLocale = config('app.locale');
    $tag = Tag::factory()->hasSupportedTranslations()->create();

    // Required error for Default locale.
    livewire(EditTagPage::class, ['record' => $tag->getRouteKey()])
        ->fillForm([
            "{$defaultLocale}.name" => null,
        ])
        ->call('save')
        ->assertHasFormErrors(["{$defaultLocale}.name" => 'required']);

    // Required error for all locales.
    $locales = config('localized-routes.supported_locales');
    $errors = [];
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => null,
            'description' => fake()->paragraph(),
        ];
        $errors["{$locale}.name"] = $locale === $defaultLocale ? 'required' : 'required_with';
    }

    livewire(EditTagPage::class, ['record' => $tag->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors($errors);

    // Unique error.
    $data = [
        $defaultLocale => [
            'name' => $tag->translation->name,
        ],
    ];
    $otherTag = Tag::factory()->hasDefaultTranslation()->create();

    livewire(EditTagPage::class, ['record' => $otherTag->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors(["{$defaultLocale}.name" => 'unique']);
});

test('tag-delete', function () {
    $tag = Tag::factory()->create();

    livewire(EditTagPage::class, [
        'record' => $tag->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($tag);
});
