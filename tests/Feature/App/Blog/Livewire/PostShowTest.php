<?php

use Livewire\Livewire;
use Domain\Blog\Models\Post;
use App\Blog\Livewire\PostShow;
use Illuminate\Support\Facades\Cache;

test('post-renders', function () {
    $post = Post::factory()
        ->published()
        ->hasDefaultTranslation()
        ->hasCategoryDefaultTranslation()
        ->create();

    Livewire::test(PostShow::class, ['slug' => $post->translation->slug])
        ->assertStatus(200)
        ->assertSee($post->translation->title)
        ->assertSee($post->translation->content)
        ->assertSee(ucwords($post->category->translation->name));

    // Check if list stored in cache.
    $cache = Cache::store();
    // Check if the current cache driver supports tags.
    $key = Post::CACHE_KEY . ".{$post->translation->slug}";
    if (method_exists($cache->getStore(), 'tags')) {
        // If it does, use tags to retrieve the post.
        expect(Cache::tags(Post::CACHE_KEY)->get($key))->not->toBeNull();
        return;
    }

    // If it doesn't, retrieve the post without tags.
    expect(Cache::get($key))->not->toBeNull();
});

test('post-redirects-to-404', function () {
    Livewire::test(PostShow::class, ['slug' => 'non-existing-slug'])->assertStatus(404);
});

test('post-redirects-to-correct-locale', function () {
    $post = Post::factory()
        ->published()
        ->hasSupportedTranslations()
        ->hasCategoryWithSupportedTranslations()
        ->create();
    $postLocale = $post->translations->where('locale', '!=', app()->getLocale())->first()->locale;

    Livewire::test(PostShow::class, ['slug' => $post->translations->where('locale', $postLocale)->first()->slug])
        ->assertRedirect(route("{$postLocale}.blog.posts.show", [
            'slug' => $post->translations->where('locale', $postLocale)->first()->slug,
        ]));
});

test('post-unpublished-redirects-to-404', function () {
    $post = Post::factory()->unpublished()->hasDefaultTranslation()->create();

    Livewire::test(PostShow::class, ['slug' => $post->translation->slug])->assertStatus(404);
});
