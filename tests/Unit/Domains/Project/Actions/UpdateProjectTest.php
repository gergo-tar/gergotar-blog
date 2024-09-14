<?php

use Domain\Project\Models\Project;
use Illuminate\Support\Facades\Cache;
use Domain\Project\Actions\UpdateProject;
use Domain\Project\Actions\GetActiveProjects;

test('update-project', function () {
    // Create active projects
    Project::factory(10)->active()->hasSupportedTranslations()->create();
    // Create the project.
    $project = Project::factory()
        ->active()
        ->hasSupportedTranslations()
        ->create([
            'url' => 'https://example.com',
        ]);

    // Cache the projects.
    GetActiveProjects::run();

    // Check if the project is cached.
    $cacheKey = (new GetActiveProjects())->cacheKey() . '.' . app()->getLocale();
    expect(Cache::has($cacheKey))->toBeTrue();

    // Update the project.
    $title = 'Updated Project Title';
    $url = 'https://example.com/updated';
    $data = compact('url');

    $locales = config('localized-routes.supported_locales');
    // Set category data for each supported locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => fake()->paragraph . ' ' . $locale,
        ];
    }

    $updatedProject = UpdateProject::run($project, $data);

    expect($updatedProject)->toBeInstanceOf(Project::class);
    expect($updatedProject->url)->toBe($url);
    expect($updatedProject->translations)->toHaveCount(count($locales));
    foreach ($locales as $locale) {
        $translation = $updatedProject->translations->where('locale', $locale)->first();
        expect($translation->title)->toBe($data[$locale]['title']);
    }

    // Check if the project cache is cleared.
    expect(Cache::has($cacheKey))->toBeFalse();
});
