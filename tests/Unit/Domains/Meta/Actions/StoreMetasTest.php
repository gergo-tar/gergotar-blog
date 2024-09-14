<?php

use Domain\Blog\Models\Post;
use Domain\Meta\Actions\StoreMetas;
use Domain\Meta\Models\Meta;
use Illuminate\Support\Collection;

test('store-metas', function () {
    $model = Post::factory()->create();

    $data = [
        'key1' => 'value1',
        'key2' => 'value2',
    ];

    // Store the metas
    $metas = StoreMetas::run($model, $data);

    // Check if the return value is a collection
    expect($metas)->toBeInstanceOf(Collection::class);
    expect($metas->count())->toBe(2);

    // Check if the metas are stored in the database
    $meta = Meta::where('key', 'key1')->first();
    expect($metas->first()->key)->toBe($meta->key);
    expect($metas->first()->value)->toBe($meta->value);

    $meta = Meta::where('key', 'key2')->first();
    expect($metas->last()->key)->toBe($meta->key);
    expect($metas->last()->value)->toBe($meta->value);
});
