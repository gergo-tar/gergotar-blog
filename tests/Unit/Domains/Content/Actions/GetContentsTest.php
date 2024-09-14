<?php

use Domain\Content\Models\Content;
use Domain\Content\Actions\GetContents;

test('get-contents', function () {
    // Create contents.
    Content::factory(3)
        ->hasSupportedTranslations()
        ->create();

    // Get the contents.
    $contents = GetContents::run();

    // Check if the right contents are returned.
    expect($contents->count())->toBe(3);
    $contents->each(function ($content) {
        expect($content->title)->not->toBeNull();
        expect($content->slug)->not->toBeNull();
        expect($content->translation->content)->not->toBeNull();
    });
});
