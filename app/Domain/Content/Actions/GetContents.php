<?php

namespace Domain\Content\Actions;

use Domain\Content\Models\Content;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Database\Eloquent\Collection;

class GetContents
{
    use AsAction;

    /**
     * Get Contents.
     *
     * @param  array  $select  The columns to select.
     * @param  array  $with  The with scopes to eager load relationships. See ContentQueryBuilder.
     */
    public function handle(array $select = ['*'], array $with = []): Collection
    {
        return Content::select($select)
            ->when(!empty($with), function ($query) use ($with) {
                foreach ($with as $key => $select) {
                    $scope = 'with' . ucfirst($key);
                    // Check if scope exists before calling it.
                    if (method_exists($query, $scope)) {
                        $query->$scope($select);
                    }
                }
            })
            ->get();
    }
}
