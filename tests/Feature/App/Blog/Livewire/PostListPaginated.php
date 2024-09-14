<?php

use Livewire\Livewire;
use Domain\Blog\Models\Post;
use Illuminate\Support\Facades\Cache;
use App\Blog\Livewire\PostListPaginated;

test('post-list-paginated-renders', function () {
    $posts = Post::factory()->count(6)
        ->published()
        ->hasDefaultTranslation()
        ->hasCategoryDefaultTranslation()
        ->hasTagDefaultTranslation()
        ->create();

    $limit = 3;
    Livewire::test(PostListPaginated::class, ['limit' => $limit])
        ->assertStatus(200)
        ->assertViewHas('posts', fn ($value) => $value->count() === $limit)
        ->assertSee($posts->first()->translation->title)
        ->assertSee($posts->first()->translation->excerpt)
        ->assertSee($posts[2]->translation->title)
        ->assertSee($posts[2]->translation->excerpt);

    // Check if list stored in cache.
    $cache = Cache::store();
    // Only use caching if tags are supported.
    if (method_exists($cache->getStore(), 'tags')) {
        $key = "page.1.limit.{$limit}";
        expect(Cache::tags(Post::CACHE_KEY)->get($key))->not->toBeNull();
    }
});
