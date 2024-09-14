<?php

use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectTranslation;

test('project-translation-has-project-model', function () {
    $project = Project::factory()->create();
    $translation = ProjectTranslation::factory()->create([
        'project_id' => $project->id,
    ]);

    // Check if the translation has the right project.
    expect($translation->project)->toBeInstanceOf(Project::class);
    expect($translation->project->id)->toBe($project->id);
});
