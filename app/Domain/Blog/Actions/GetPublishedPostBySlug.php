<?php

namespace Domain\Blog\Actions;

use Domain\Blog\Models\Post;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPublishedPostBySlug
{
    use AsAction;

    /**
     * Get Published Post by Slug.
     *
     * @param  string  $slug  The post slug.
     * @param  array  $select  The columns to select.
     */
    public function handle(string $slug, array $select = ['*']): ?Post
    {
        // @phpstan-ignore-next-line
        return Post::select($select)
            ->wherePublished()
            ->whereTranslationSlug($slug)
            ->withTranslations(
                ['post_id', 'locale', 'title', 'slug', 'content', 'excerpt'],
                $slug
            )
            ->withTranslationsMetas(['metable_type', 'metable_id', 'key', 'value'])
            ->first();
    }
}
