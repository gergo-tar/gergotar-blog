<?php

namespace Database\Seeders;

use Domain\Blog\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Post::factory(10)
            ->published()
            ->hasCategoryWithSupportedTranslations()
            ->hasFeaturedImage()
            ->hasSupportedTranslationsWithMetas()
            ->hasTagDefaultTranslation()
            ->create();
    }
}
