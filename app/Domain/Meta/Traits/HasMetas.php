<?php

namespace Domain\Meta\Traits;

use Domain\Meta\Models\Meta;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasMetas
{
    /**
     * Get the metas for the model.
     */
    public function metas(): HasMany
    {
        return $this->hasMany(Meta::class, 'metable_id')
            ->where('metable_type', static::class);
    }

    /**
     * Load the metas relationship.
     *
     * @param  array<string>  $columns
     * @return $this
     */
    public function loadMetas(array $columns = ['*']): self
    {
        return $this->load([
            'metas' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }
}
