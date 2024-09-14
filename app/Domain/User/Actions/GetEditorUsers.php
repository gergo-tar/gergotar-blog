<?php

namespace Domain\User\Actions;

use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetEditorUsers
{
    use AsAction;

    /**
     * Get all users with editor role.
     */
    public function handle(array $select = ['*']): Collection
    {
        // TODO filter by editor role.
        return User::all($select);
    }
}
