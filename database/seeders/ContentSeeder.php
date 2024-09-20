<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Domain\Content\Models\Content;
use Domain\Content\Models\ContentTranslation;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locales = config('localized-routes.supported_locales');

        // Intro
        if (! Content::where('slug', 'intro')->exists()) {
            $intro = Content::factory()->intro()->create();
            foreach ($locales as $locale) {
                $intro->translations()->save(
                    ContentTranslation::factory()
                        ->state([
                            'locale' => $locale,
                            'content' => __('Page::home.content', [], $locale),
                        ])->make()
                );
            }
        }

        // About me
        if (! Content::where('slug', 'about-me')->exists()) {
            $aboutMe = Content::factory()->about()->create();
            foreach ($locales as $locale) {
                $aboutMe->translations()->save(
                    ContentTranslation::factory()
                        ->state([
                            'locale' => $locale,
                            'content' => __('Page::about.content', [], $locale),
                        ])->make()
                );
            }
        }

        // Contact
        if (! Content::where('slug', 'contact')->exists()) {
            $contact = Content::factory()->contact()->create();
            foreach ($locales as $locale) {
                $contact->translations()->save(
                    ContentTranslation::factory()
                        ->state([
                            'locale' => $locale,
                            'content' => __('Page::contact.content', [], $locale),
                        ])->make()
                );
            }
        }

        // Blog
        if (! Content::where('slug', 'blog')->exists()) {
            $blog = Content::factory()->blog()->create();
            foreach ($locales as $locale) {
                $blog->translations()->save(
                    ContentTranslation::factory()
                        ->state([
                            'locale' => $locale,
                            'content' => __('Blog::blog.about.content', [], $locale),
                        ])->make()
                );
            }
        }
    }
}
