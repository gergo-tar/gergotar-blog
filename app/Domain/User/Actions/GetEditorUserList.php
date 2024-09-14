<?php

namespace Domain\User\Actions;

use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class GetEditorUserList
{
    use AsAction;

    /**
     * Get the User list for select list.
     */
    public function handle(): Collection
    {
        return GetEditorUsers::run(['id', 'name'])->pluck('name', 'id');
    }
}
