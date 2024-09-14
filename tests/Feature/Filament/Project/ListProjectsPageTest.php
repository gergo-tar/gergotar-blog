<?php

use Domain\User\Models\User;
use Domain\Project\Models\Project;
use App\Filament\Resources\Project\ProjectResource;
use App\Filament\Resources\Project\ProjectResource\Pages\ListProjectsPage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('project-table-renders', function () {
    $this->get(ProjectResource::getUrl('index'))->assertSuccessful();
});

test('project-table-list-projects', function () {
    $projects = Project::factory(10)->hasSupportedTranslations()->create();

    livewire(ListProjectsPage::class)
        ->assertCanSeeTableRecords($projects);
});
