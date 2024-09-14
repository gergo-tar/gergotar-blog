<?php

use Domain\User\Models\User;
use Filament\Actions\DeleteAction;
use Domain\Project\Models\Project;
use App\Filament\Resources\Project\ProjectResource;
use App\Filament\Resources\Project\ProjectResource\Pages\EditProjectPage;

use function Pest\Faker\fake;
use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('project-edit-renders', function () {
    $this->get(
        ProjectResource::getUrl(
            'edit',
            ['record' => Project::factory()->create()]
        )
    )->assertSuccessful();
});

test('project-edit-data', function () {
    $project = Project::factory()->hasSupportedTranslations()->create();

    $data = [
        'url' => $project->url,
    ];
    foreach ($project->translations as $translation) {
        $data[$translation->locale] = [
            'title' => $translation->title,
            'description' => $translation->description,
        ];
    }

    // Check if form is filled with the correct data.
    livewire(EditProjectPage::class, ['record' => $project->getRouteKey()])
        ->assertFormSet($data);
});

test('project-edit-save', function () {
    $project = Project::factory()->hasSupportedTranslations()->create();

    $url = fake()->unique()->url();
    $data = compact('url');
    foreach ($project->translations as $translation) {
        $data[$translation->locale] = [
            'title' => fake()->unique()->word(),
            'description' => fake()->paragraph(),
        ];
    }

    livewire(EditProjectPage::class, ['record' => $project->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasNoFormErrors();

    // Check if project was updated with the correct translations.
    $project->refresh()->translations->each(function ($translation) use ($data) {
        expect($data[$translation->locale])->toBe([
            'title' => $translation->title,
            'description' => $translation->description,
        ]);
    });
});

test('project-edit-validation', function () {
    $defaultLocale = config('app.locale');
    $project = Project::factory()->hasSupportedTranslations()->create();

    // Required error for Default locale.
    livewire(EditProjectPage::class, ['record' => $project->getRouteKey()])
        ->fillForm([
            'url' => null,
            "{$defaultLocale}.title" => null,
        ])
        ->call('save')
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
            'title' => null,
            'description' => fake()->paragraph(),
        ];
        $errors["{$locale}.title"] = $locale === $defaultLocale ? 'required' : 'required_with';
    }

    livewire(EditProjectPage::class, ['record' => $project->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors($errors);

    // Unique error.
    $data = [
        $defaultLocale => [
            'title' => $project->translation->title,
        ],
    ];
    $otherProject = Project::factory()->hasDefaultTranslation()->create();

    livewire(EditProjectPage::class, ['record' => $otherProject->getRouteKey()])
        ->fillForm($data)
        ->call('save')
        ->assertHasFormErrors(["{$defaultLocale}.title" => 'unique']);
});

test('project-delete', function () {
    $project = Project::factory()->create();

    livewire(EditProjectPage::class, [
        'record' => $project->getRouteKey(),
    ])
        ->callAction(DeleteAction::class);

    $this->assertModelMissing($project);
});
