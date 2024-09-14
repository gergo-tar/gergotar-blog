<?php

use Domain\Project\Models\Project;

test('where-active', function () {
    // Create an inactive project
    Project::factory()->inactive()->create();
    // Create an active project
    $project = Project::factory()->active()->create();

    $projects = Project::query()->whereActive()->get();

    expect($projects->count())->toBe(1);
    expect($projects->first()->id)->toBe($project->id);
});

test('where-inactive', function () {
    // Create an active project
    Project::factory()->active()->create();
    // Create an inactive project
    $project = Project::factory()->inactive()->create();

    $projects = Project::query()->whereInactive()->get();

    expect($projects->count())->toBe(1);
    expect($projects->first()->id)->toBe($project->id);
});
