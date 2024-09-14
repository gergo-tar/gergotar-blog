<?php

namespace Domain\Tag\Traits;

use Domain\Tag\Models\Tag;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasTags
{
    /**
     * Get the tags for the model.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable', 'taggables', 'taggable_id', 'tag_id')
            ->where('taggable_type', static::class)
            ->withTimestamps();
    }

    /**
     * Load the tags relationship.
     *
     * @param  array<string>  $columns
     * @return $this
     */
    public function loadTags(array $columns = ['*']): self
    {
        return $this->load([
            'tags' => function ($query) {
                $table = $query->getModel()->getTable();
                $query->select("{$table}.id");
            },
            'tags.translation' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }
}
