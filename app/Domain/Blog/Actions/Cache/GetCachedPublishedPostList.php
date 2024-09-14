<?php

namespace Domain\Blog\Actions\Cache;

use Domain\Blog\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Blog\Actions\GetPublishedPosts;
use Illuminate\Pagination\LengthAwarePaginator;

class GetCachedPublishedPostList
{
    use AsAction;

    /**
     * Get Cached Published Post.
     *
     * @param  bool  $isPaginated  Whether to paginate the results.
     * @param  int  $limit  The number of posts to get.
     * @param  int|null  $page  The page number.
     */
    public function handle(bool $isPaginated, int $limit, ?int $page = null): Collection|LengthAwarePaginator|Paginator
    {
        $cache = Cache::store();
        $select = ['id', 'author_id', 'published_at', 'featured_image_id'];
        $with = [
            'category' => null,
            'featuredImage' => ['id', 'path', 'width', 'height', 'alt'],
            'translation' => ['post_id', 'locale', 'title', 'slug', 'excerpt', 'reading_time'],
        ];

        // Only use caching if tags are supported.
        if (method_exists($cache->getStore(), 'tags')) {
            $cacheKey = $this->getCacheKey($isPaginated, $limit, $page);
            return Cache::tags(Post::CACHE_KEY)
                ->remember($cacheKey, config('cache.ttl'), function () use ($isPaginated, $limit, $select, $with) {
                    return GetPublishedPosts::run(
                        $isPaginated,
                        $limit,
                        $select,
                        $with
                    );
                });
        }

        // Fallback to fetching posts without caching.
        return GetPublishedPosts::run($isPaginated, $limit, $select, $with);
    }

    /**
     * Get the cache key for the posts.
     *
     * @param  bool  $isPaginated  Whether to paginate the results.
     * @param  int  $limit  The number of posts to get.
     * @param  int|null  $page  The page number.
     */
    private function getCacheKey(bool $isPaginated, int $limit, ?int $page = null): string
    {
        return $isPaginated
            ? 'page.' . ($page ?? 1) . '.limit.' . $limit
            : 'list.limit.' . $limit;
    }
}
