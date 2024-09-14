<?php

use Domain\Blog\Models\Post;
use Domain\Blog\Models\PostTranslation;
use Domain\Translation\Models\AbstractTranslation;
use Domain\User\Models\User;

test('post-has-translations-trait', function () {
    $model = Post::factory()->create();
    $user = User::factory()->create();
    $locales = config('localized-routes.supported_locales');

    foreach ($locales as $locale) {
        $translation = new PostTranslation([
            'locale' => $locale,
            'title' => "Post Title {$locale}",
            'content' => "Post Content {$locale}",
            'excerpt' => "Post Excerpt {$locale}",
            'created_by' => $user->id,
            'updated_by' => $user->id,
        ]);

        $model->translations()->save($translation);
    }

    $model->loadTranslation();
    // Check if the default locale translation is loaded
    $locale = config('app.locale');
    expect($model->relationLoaded('translation'))->toBeTrue();
    expect($model->translation)->toBeInstanceOf(AbstractTranslation::class);
    expect($model->translation->title)->toBe("Post Title {$locale}");
    expect($model->translation->content)->toBe("Post Content {$locale}");

    $model->loadTranslations();
    // Check if the translations are loaded
    expect($model->relationLoaded('translations'))->toBeTrue();
    expect($model->translations->count())->toBe(count($locales));
    $model->translations->each(function ($translation) {
        $locale = $translation->locale;
        expect($translation)->toBeInstanceOf(AbstractTranslation::class);
        expect($translation->title)->toBe("Post Title {$locale}");
        expect($translation->content)->toBe("Post Content {$locale}");
    });
});
