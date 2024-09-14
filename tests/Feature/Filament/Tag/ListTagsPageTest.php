<?php

use Domain\User\Models\User;
use Domain\Tag\Models\Tag;
use App\Filament\Resources\Tag\TagResource;
use App\Filament\Resources\Tag\TagResource\Pages\ListTagsPage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('tag-table-renders', function () {
    $this->get(TagResource::getUrl('index'))->assertSuccessful();
});

test('tag-table-list-categories', function () {
    $categories = Tag::factory(10)->hasSupportedTranslations()->create();

    livewire(ListTagsPage::class)
        ->assertCanSeeTableRecords($categories);
});
