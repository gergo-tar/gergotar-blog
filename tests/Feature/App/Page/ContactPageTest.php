<?php

use Domain\Content\Models\Content;

test('contact-renders-with-meta', function () {
    $title = 'Meta title';
    $description = 'Meta description';

    $content = Content::factory()
        ->contact()
        ->hasSupportedTranslationsWithMetas(
            2,
            [
                'key' => 'description',
                'value' => $description,
            ]
        )->create();
    // Update one meta to be the title.
    $content->translation->metas->first()->update(['key' => 'title', 'value' => $title]);

    $author = get_blog_owner_name(app()->getLocale());

    $this->get(route('contact'))
        ->assertOk()
        ->assertSee("<title>{$title} | {$author}</title>", false)
        ->assertSee("<meta name=\"description\" content=\"{$description}\">", false);
});
