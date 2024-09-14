<?php

use Livewire\Livewire;
use Domain\Project\Models\Project;
use App\Project\Livewire\ProjectList;
use Illuminate\Support\Facades\Cache;
use Domain\Project\Actions\GetActiveProjects;

test('project-list-renders', function () {
    $limit = 6;
    // Create inactive projects.
    Project::factory()->inactive()
        ->hasDefaultTranslation()
        ->create();

    $projects = Project::factory()->count($limit)
        ->active()
        ->hasDefaultTranslation()
        ->create();

    Livewire::test(ProjectList::class)
        ->assertStatus(200)
        ->assertViewHas('projects', fn ($value) => $value->count() === $limit)
        ->assertSee($projects->first()->translation->title)
        ->assertSee($projects->first()->translation->description)
        ->assertSee($projects->last()->translation->title)
        ->assertSee($projects->last()->translation->description);

    // Check if list stored in cache.
    $cache = Cache::store();
    $this->assertTrue($cache->has(GetActiveProjects::cacheKey() . '.' . app()->getLocale()));
});
