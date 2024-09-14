<?php

use Domain\User\Models\User;
use Domain\Blog\Models\Post;
use App\Filament\Resources\Blog\PostResource;
use App\Filament\Resources\Blog\PostResource\Pages\ListPostsPage;

use function Pest\Livewire\livewire;

beforeEach(function () {
    $this->actingAs(User::factory()->filamentUser()->create());
});

test('post-table-renders', function () {
    $this->get(PostResource::getUrl('index'))->assertSuccessful();
});

test('post-table-list-posts', function () {
    $posts = Post::factory(10)->hasSupportedTranslations()->create();

    livewire(ListPostsPage::class)
        ->assertCanSeeTableRecords($posts);
});
