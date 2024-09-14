<?php

namespace Domain\Blog\Traits;

use Domain\Blog\Models\PostAuthor;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasPostAuthor
{
    /**
     * Get the author that owns the post.
     */
    public function author(): HasOne
    {
        return $this->hasOne(PostAuthor::class, 'id', 'author_id');
    }

    /**
     * Load the author relationship.
     *
     * @param  array<string>  $columns
     * @return $this
     */
    public function loadAuthor(array $columns = ['*']): self
    {
        return $this->load([
            'author' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }
}
