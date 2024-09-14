<?php

use App\Blog\Livewire\PostItem;
use Domain\Blog\Models\Post;
use Livewire\Livewire;

test('post-item-renders', function () {
    $post = Post::factory()
        ->published()
        ->hasDefaultTranslation()
        ->hasCategoryDefaultTranslation()
        ->hasTagDefaultTranslation()
        ->create();

    Livewire::test(PostItem::class, ['post' => $post])
        ->assertStatus(200)
        ->assertSee($post->translation->title)
        ->assertSee($post->translation->excerpt);
});
