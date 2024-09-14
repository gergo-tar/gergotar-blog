<?php

use Domain\User\Models\User;
use Domain\Content\Models\Content;
use App\Filament\Resources\Content\ContentResource;
use App\Filament\Resources\Content\ContentResource\Pages\ListContentsPage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('content-table-renders', function () {
    $this->get(ContentResource::getUrl('index'))->assertSuccessful();
});

test('content-table-list-contents', function () {
    $contents = Content::factory(10)->hasSupportedTranslations()->create();

    livewire(ListContentsPage::class)
        ->assertCanSeeTableRecords($contents);
});
