<?php

use Livewire\Livewire;
use Domain\Blog\Models\Post;
use App\Blog\Livewire\PostList;
use Illuminate\Support\Facades\Cache;

test('post-list-renders', function () {
    $limit = 6;
    $posts = Post::factory()->count($limit)
        ->published()
        ->hasDefaultTranslation()
        ->hasCategoryDefaultTranslation()
        ->hasTagDefaultTranslation()
        ->create();

    Livewire::test(PostList::class, ['limit' => $limit])
        ->assertStatus(200)
        ->assertViewHas('posts', fn ($value) => $value->count() === $limit)
        ->assertSee($posts->first()->translation->title)
        ->assertSee($posts->first()->translation->excerpt)
        ->assertSee($posts->last()->translation->title)
        ->assertSee($posts->last()->translation->excerpt);

    // Check if list stored in cache.
    $cache = Cache::store();
    // Only use caching if tags are supported.
    if (method_exists($cache->getStore(), 'tags')) {
        $key = "list.limit.{$limit}";
        expect(Cache::tags(Post::CACHE_KEY)->get($key))->not->toBeNull();
    }
});
