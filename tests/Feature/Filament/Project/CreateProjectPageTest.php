<?php

use Domain\User\Models\User;
use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectTranslation;
use App\Filament\Resources\Project\ProjectResource;
use App\Filament\Resources\Project\ProjectResource\Pages\CreateProjectPage;

use function Pest\Livewire\livewire;
use function Pest\Faker\fake;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('project-create-renders', function () {
    $this->get(ProjectResource::getUrl('create'))->assertSuccessful();
});

test('project-create-save', function () {
    $locales = config('localized-routes.supported_locales');
    // Create data for each locale.
    $url = fake()->unique()->url();
    $data = compact('url');

    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
        ];
    }

    // Create project trough Form.
    livewire(CreateProjectPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasNoFormErrors();

    // Check if project was created with the correct translations.
    foreach ($locales as $locale) {
        $this->assertDatabaseHas(ProjectTranslation::class, [
            'title' => $data[$locale]['title'],
            'description' => $data[$locale]['description'],
        ]);

        $translation = ProjectTranslation::where('title', $data[$locale]['title'])->first();
        $this->assertDatabaseHas(Project::class, [
            'id' => $translation->project_id,
            'url' => $url,
        ]);
    }
});

test('project-create-validation', function () {
    $defaultLocale = config('app.locale');

    // Required error for Default locale.
    livewire(CreateProjectPage::class)
        ->call('create')
        ->assertHasFormErrors([
            'url' => 'required',
            "{$defaultLocale}.title" => 'required'
        ]);

    // Required error for all locales.
    $locales = config('localized-routes.supported_locales');
    $errors = [];
    $data = ['url' => fake()->unique()->url()];
    foreach ($locales as $locale) {
        $data[$locale] = [
            'description' => fake()->paragraph(),
        ];
        $errors["{$locale}.title"] = $locale === $defaultLocale ? 'required' : 'required_with';
    }

    livewire(CreateProjectPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors($errors);

    // Unique error.
    $project = Project::factory()->hasDefaultTranslation()->create();
    $data = [
        $defaultLocale => [
            'title' => $project->translation->title,
            'description' => fake()->paragraph(),
        ],
    ];

    livewire(CreateProjectPage::class)
        ->fillForm($data)
        ->call('create')
        ->assertHasFormErrors(["{$defaultLocale}.title" => 'unique']);
});
