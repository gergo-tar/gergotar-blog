<?php

use Livewire\Livewire;
use Domain\Project\Models\Project;
use App\Project\Livewire\ProjectItem;

test('project-item-renders', function () {
    $project = Project::factory()
        ->active()
        ->hasDefaultTranslation()
        ->create();

    Livewire::test(ProjectItem::class, ['project' => $project])
        ->assertStatus(200)
        ->assertSee($project->url)
        ->assertSee($project->translation->title)
        ->assertSee($project->translation->description);
});
