<?php

use Domain\Project\Models\Project;
use Domain\Project\Actions\CreateProject;

test('create-project', function () {
    $data = [
        'url' => fake()->url,
    ];

    $locales = config('localized-routes.supported_locales');
    // Set prject data for each supported locale.
    foreach ($locales as $locale) {
        $data[$locale] = [
            'title' => fake()->sentence,
            'description' => fake()->paragraph,
        ];
    }

    $project = CreateProject::run($data);

    expect($project)->toBeInstanceOf(Project::class);
    expect($project->url)->toBe($data['url']);
    expect($project->translations)->toHaveCount(count($locales));
    foreach ($locales as $locale) {
        $translation = $project->translations->where('locale', $locale)->first();
        expect($translation->title)->toBe($data[$locale]['title']);
        expect($translation->description)->toBe($data[$locale]['description']);
    }
});
