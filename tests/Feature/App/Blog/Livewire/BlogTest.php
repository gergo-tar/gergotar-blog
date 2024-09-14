<?php

use Livewire\Livewire;
use App\Blog\Livewire\Blog;
use Domain\Content\Models\Content;

test('blog-renders', function () {
    $content = Content::factory()
        ->blog()
        ->hasSupportedTranslationsWithMetas()
        ->create();

    Livewire::test(Blog::class)
        ->assertStatus(200)
        ->assertSee($content->translation->content);
});
