<?php

namespace Domain\Blog\Actions;

use Domain\Blog\Models\Post;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class GetPublishedPosts
{
    use AsAction;

    /**
     * Get published posts.
     *
     * @param  bool  $isPaginated  Whether to paginate the results.
     * @param  int  $limit  The number of posts to get.
     * @param  array  $select  The columns to select.
     * @param  array  $with  The with scopes to eager load relationships. See PostQueryBuilder.
     */
    public function handle(
        bool $isPaginated,
        int $limit = 6,
        array $select = ['*'],
        array $with = []
    ): Collection|LengthAwarePaginator {
        return $this->fetchPosts($isPaginated, $limit, $select, $with);
    }

    /**
     * Fetch posts based on pagination and limit settings.
     *
     * @param  bool  $isPaginated  Whether to paginate the results.
     * @param  int  $limit  The number of posts to get.
     * @param  array  $select  The columns to select.
     * @param  array  $with  The with scopes to eager load relationships. See PostQueryBuilder.
     */
    private function fetchPosts(
        bool $isPaginated,
        int $limit,
        array $select = ['*'],
        array $with = []
    ): Collection|LengthAwarePaginator {
        $query = $this->getPublishedPostQuery($select, $with);

        if ($isPaginated) {
            return $query->paginate($limit);
        }

        return $query
            ->when($limit > 0, fn ($query) => $query->limit($limit))
            ->get();
    }

    /**
     * Get published posts query.
     *
     * @param  array  $select  The columns to select.
     * @param  array  $with  The with scopes to eager load relationships. See PostQueryBuilder.
     */
    private function getPublishedPostQuery(array $select = ['*'], array $with = []): Builder
    {
        return Post::select($select)
            ->wherePublished()
            ->orderByPublishedAt()
            ->when(!empty($with), function ($query) use ($with) {
                foreach ($with as $key => $select) {
                    $scope = 'with' . ucfirst($key);
                    // Check if scope exists before calling it.
                    if (method_exists($query, $scope)) {
                        $query->$scope($select);
                    }
                }
            });
    }
}
