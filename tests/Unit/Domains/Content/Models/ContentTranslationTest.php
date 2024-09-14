<?php

use Domain\Content\Models\Content;
use Domain\Content\Models\ContentTranslation;

test('content-translation-has-content-model', function () {
    $content = Content::factory()->create();
    $translation = ContentTranslation::factory()->create([
        'content_id' => $content->id,
    ]);

    // Check if the translation has the right content.
    expect($translation->contentModel)->toBeInstanceOf(Content::class);
    expect($translation->contentModel->id)->toBe($content->id);
});
