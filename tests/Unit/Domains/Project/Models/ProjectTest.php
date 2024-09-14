<?php

use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectTranslation;

test('project-has-translation', function () {
    $locales = config('localized-routes.supported_locales');
    $project = Project::factory()->hasSupportedTranslations()->create();

    // Check if the project has the right amount of translations.
    expect($project->translations->count())->toBe(count($locales));
    // Check if the translations are instances of the ProjectTranslation model.
    $project->translations->each(function ($translation) use ($locales) {
        expect($locales)->toContain($translation->locale);
        expect($translation)->toBeInstanceOf(ProjectTranslation::class);
    });
});
