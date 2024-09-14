<?php

namespace Domain\Blog\Actions;

use Domain\Blog\Models\Post;
use Lorisleiva\Actions\Concerns\AsAction;

class SyncPostRelationships
{
    use AsAction;

    /**
     * Create the Post record.
     *
     * @param  Post  $record  The post model.
     * @param  array  $data  The data to sync.
     */
    public function handle(Post $record, array $data): void
    {
        // Sync tags.
        if (isset($data['tags'])) {
            $record->tags()->sync($data['tags']);
        }

        // Sync category.
        if (isset($data['category_id'])) {
            $record->categories()->sync([$data['category_id']]);
        }
    }
}
