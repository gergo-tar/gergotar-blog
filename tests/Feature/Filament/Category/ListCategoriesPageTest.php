<?php

use Domain\User\Models\User;
use Domain\Category\Models\Category;
use App\Filament\Resources\Category\CategoryResource;
use App\Filament\Resources\Category\CategoryResource\Pages\ListCategoriesPage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('category-table-renders', function () {
    $this->get(CategoryResource::getUrl('index'))->assertSuccessful();
});

test('category-table-list-categories', function () {
    $categories = Category::factory(10)->hasSupportedTranslations()->create();

    livewire(ListCategoriesPage::class)
        ->assertCanSeeTableRecords($categories);
});
