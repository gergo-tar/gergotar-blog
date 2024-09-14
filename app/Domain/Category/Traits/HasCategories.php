<?php

namespace Domain\Category\Traits;

use Domain\Category\Models\Category;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait HasCategories
{
    /**
     * Get the categories for the model.
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categoriable', 'categoriables', 'categoriable_id', 'category_id')
            ->where('categoriable_type', static::class)
            ->withTimestamps();
    }
}
