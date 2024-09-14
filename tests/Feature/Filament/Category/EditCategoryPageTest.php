<?php

use Domain\User\Models\User;
use Filament\Actions\DeleteAction;
use Domain\Category\Models\Category;
use App\Filament\Resources\Category\CategoryResource;
use App\Filament\Resources\Category\CategoryResource\Pages\EditCategoryPage;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('category-edit-renders', function () {
    $this->get(
        CategoryResource::getUrl(
            'edit',
            ['record' => Category::factory()->create()]
        )
    )->assertSuccessful();
});

test('category-edit-data', function () {
    $category = Category::factory()->hasSupportedTranslations()->create();

    $data = [];
    foreach ($category->translations as $translation) {
        $data[$translation->locale] = [
            'name' => $translation->name,
            'description' => $translation->description,
        ];
    }

    // Check if form is filled with the correct data.
    livewire(EditCategoryPage::class, ['record' => $category->getRouteKey()])
        ->assertFormSet($data);
});

test('category-edit-save', function () {
    $category = Category::factory()->hasSupportedTranslations()->create();

    $data = [];
    foreach ($category->translations as $translation) {
        $data[$translation->locale] = [
            'name' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
        ];
    }

    livewire(EditCategoryPage::class, ['record' => $category->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasNoFormErrors();

    // Check if category was updated with the correct translations.
    $category->refresh()->translations->each(function ($translation) use ($data) {
        expect($data[$translation->locale])->toBe([
            'name' => $translation->name,
            'description' => $translation->description,
        ]);
    });
});

test('category-edit-validation', function () {
    $defaultLocale = config('app.locale');
    $category = Category::factory()->hasSupportedTranslations()->create();

    // Required error for Default locale.
    livewire(EditCategoryPage::class, ['record' => $category->getRouteKey()])
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

    livewire(EditCategoryPage::class, ['record' => $category->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors($errors);

    // Unique error.
    $data = [
        $defaultLocale => [
            'name' => $category->translation->name,
        ],
    ];
    $otherCategory = Category::factory()->hasDefaultTranslation()->create();

    livewire(EditCategoryPage::class, ['record' => $otherCategory->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors(["{$defaultLocale}.name" => 'unique']);
});

test('category-delete', function () {
    $category = Category::factory()->create();

    livewire(EditCategoryPage::class, [
        'record' => $category->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($category);
});
