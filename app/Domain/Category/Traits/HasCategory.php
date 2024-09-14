<?php

namespace Domain\Category\Traits;

use Domain\Category\Models\Categoriable;
use Domain\Category\Models\Category;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

trait HasCategory
{
    use HasCategories;

    /**
     * Get the model's category.
     */
    public function category(): HasOneThrough
    {
        return $this->hasOneThrough(
            Category::class,
            Categoriable::class,
            'categoriable_id', // Foreign key on the categoriables table...
            'id', // Foreign key on the categories table...
            'id', // Local key on the posts table...
            'category_id' // Local key on the categoriables table...
        )->where('categoriable_type', static::class);
    }

    /**
     * Load the category relationship.
     *
     * @param  array<string>  $columns
     * @return $this
     */
    public function loadCategory(array $columns = ['*']): self
    {
        return $this->load([
            'category' => function ($query) {
                $table = $query->getModel()->getTable();
                $query->select("{$table}.id");
            },
            'category.translation' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }
}
