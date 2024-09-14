<?php

namespace Domain\Blog\Actions;

use Domain\Blog\Models\Post;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Blog\Actions\Cache\ClearPostCache;
use Domain\Translation\Actions\StoreTranslations;
use Domain\Blog\Actions\Cache\ClearCountPublishedPostsCache;

class SavePost
{
    use AsAction;

    /**
     * Create the Post record.
     *
     * @param array $data  The data to create the Post record.
     * @param Post|null $post  The Post model.
     */
    public function handle(array $data, Post $post = null): Post
    {
        $isPublished = false;
        $isRecentlyCreated = true;
        if ($post) {
            $isPublished = $post->is_published;
            $isRecentlyCreated = false;
        }

        // Set default data values.
        $data = $this->setDefaultDataValues($data);

        $dataCollection = collect($data);
        // Create or update the post.
        $postData = $dataCollection->only((new Post())->getFillable())->toArray();

        $post = $this->updatePostData($postData, $post);

        // Store its translations.
        StoreTranslations::run(
            $post,
            $dataCollection->only(config('localized-routes.supported_locales'))->toArray()
        );

        // Sync tags & category.
        SyncPostRelationships::run($post, $data);

        // Clear the cache for the post list if it is a new post.
        // If it is an existing post, then clear the cache for the post.
        $isNewPublished = $isRecentlyCreated || $isPublished !== $post->is_published;
        ClearPostCache::run($isNewPublished ? null : $post);
        ClearCountPublishedPostsCache::run();

        return $post->loadTranslations([
            'post_id',
            'locale',
            'title',
            'slug',
            'content',
            'excerpt',
        ])->loadTranslationsMetas([
            'metable_id',
            'metable_type',
            'key',
            'value',
        ])->loadFeaturedImage();
    }


    /**
     * Set default data values.
     *
     * @param  array  $data  The data.
     */
    public function setDefaultDataValues(array $data): array
    {
        $data['author_id'] = $data['author_id'] ?? auth()->id();

        if ($data['is_published']) {
            $data['published_at'] = $data['published_at'] ?? now();
        }

        foreach (config('localized-routes.supported_locales') as $language) {
            if (! isset($data[$language])) {
                continue;
            }

            $data[$language]['meta']['title'] ??= $data[$language]['title'];
            $data[$language]['excerpt'] ??= substr(
                strip_tags($data[$language]['content']),
                0,
                160
            );
            $data[$language]['meta']['description'] ??= $data[$language]['excerpt'];
        }

        return $data;
    }

    /**
     * Update the post data.
     *
     * @param  array  $postData  The post data.
     * @param  Post|null  $post  The post model.
     */
    private function updatePostData(array $postData, Post $post = null): Post
    {
        if (! $post) {
            /** @var Post $post */
            $post = Post::create($postData);
            return $post;
        }

        $post->update($postData);

        // Clear the post translation cache first.
        ClearPostCache::run($post);

        /** @var Post $post */
        return $post;
    }
}
