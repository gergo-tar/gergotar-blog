<?php

use Domain\Tag\Models\Tag;
use Domain\Tag\Models\TagTranslation;
use Domain\Tag\Traits\HasTags;
use Illuminate\Database\Eloquent\Model;

test('has-tags-trait', function () {
    // Create a class on the fly that uses the HasTags trait.
    $model = new class () extends Model {
        use HasTags;
    };
    $model->id = 1;

    $count = 3;
    $tags = Tag::factory()->hasSupportedTranslations()->count($count)->create();

    $model->tags()->sync($tags);

    // Check if the model has the right tags.
    $model->loadTags();

    expect($model->relationLoaded('tags'))->toBeTrue();
    expect($model->tags->count())->toBe($count);

    $model->tags->each(function ($tag) use ($tags) {
        $selectedTag = $tags->where('id', $tag->id)->first();
        expect($tag->key)->toBe($selectedTag->key);
        expect($tag)->toBeInstanceOf(Tag::class);
        expect($tag->relationLoaded('translation'))->toBeTrue();
        expect($tag->translation)->toBeInstanceOf(TagTranslation::class);
        expect($tag->translation->name)->toBe($selectedTag->translation->name);
    });
});
