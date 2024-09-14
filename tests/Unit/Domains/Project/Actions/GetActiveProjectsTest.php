<?php

use Domain\Project\Models\Project;
use Illuminate\Support\Facades\Cache;
use Domain\Project\Actions\GetActiveProjects;

test('get-active-project-list', function () {
    $count = 10;
    // Create inactive Projects
    Project::factory($count)->inactive()->hasSupportedTranslations()->create();
    // Create active Projects
    $projects = Project::factory($count)->active()->hasSupportedTranslations()->create();
    $translations = $projects->pluck('translation.title', 'id');

    $list = GetActiveProjects::run();
    // dd($translations, $list);
    // Check if the list contains the right projects.
    expect($list->count())->toBe($count);
    $list->each(function ($project) use ($translations) {
        expect($translations)->toHaveKey($project->id);
        expect($project->translation->title)->toBe($translations[$project->id]);
    });

    // Check if the list is in the cache.
    $cacheKey = (new GetActiveProjects())->cacheKey() . '.' . app()->getLocale();
    expect(Cache::get($cacheKey))->toBe($list);
});
