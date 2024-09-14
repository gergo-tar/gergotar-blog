<?php

use Domain\Tag\Models\Tag;
use Domain\Tag\Models\TagTranslation;

test('tag-translation-has-tag', function () {
    $tag = Tag::factory()->create();
    $translation = TagTranslation::factory()->create([
        'tag_id' => $tag->id,
    ]);

    // Check if the translation has the right tag.
    expect($translation->tag)->toBeInstanceOf(Tag::class);
    expect($translation->tag->id)->toBe($tag->id);
});
