<?php

use Domain\Project\Models\Project;
use Illuminate\Support\Facades\Cache;
use Domain\Project\Actions\GetActiveProjects;
use Domain\Project\Actions\Cache\CleareActiveProjectCache;

test('clear-project-list-cache', function () {
    $count = 10;
    Project::factory($count)->active()->hasSupportedTranslations()->create();

    $list = GetActiveProjects::run();
    $cacheKey = (new GetActiveProjects())->cacheKey() . '.' . app()->getLocale();

    // Check if the list is in the cache.
    expect(Cache::get($cacheKey))->toBe($list);

    // Clear the list from the cache.
    CleareActiveProjectCache::run();

    // Check if the list is no longer in the cache.
    expect(Cache::get($cacheKey))->toBeNull();
});
