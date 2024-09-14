<?php

use Domain\User\Models\User;
use Domain\Category\Models\Category;
use Domain\Category\Models\CategoryTranslation;
use App\Filament\Resources\Category\CategoryResource;
use App\Filament\Resources\Category\CategoryResource\Pages\CreateCategoryPage;

use function Pest\Livewire\livewire;
use function Pest\Faker\fake;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('category-create-renders', function () {
    $this->get(CategoryResource::getUrl('create'))->assertSuccessful();
});

test('category-create-save', function () {
    $locales = config('localized-routes.supported_locales');
    // Create data for each locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'name' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
        ];
    }

    // Create category trough Form.
    livewire(CreateCategoryPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoFormErrors();

    // Check if category was created with the correct translations.
    foreach ($locales as $locale) {
        $this->assertDatabaseHas(CategoryTranslation::class, [
            'name' => $data[$locale]['name'],
            'description' => $data[$locale]['description'],
        ]);

        $translation = CategoryTranslation::where('name', $data[$locale]['name'])->first();
        $this->assertDatabaseHas(Category::class, [
            'id' => $translation->category_id,
        ]);
    }
});

test('category-create-validation', function () {
    $defaultLocale = config('app.locale');

    // Required error for Default locale.
    livewire(CreateCategoryPage::class)
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

    livewire(CreateCategoryPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors($errors);

    // Unique error.
    $category = Category::factory()->hasDefaultTranslation()->create();
    $data = [
        $defaultLocale => [
            'name' => $category->translation->name,
            'description' => fake()->paragraph(),
        ],
    ];

    livewire(CreateCategoryPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors(["{$defaultLocale}.name" => 'unique']);
});
