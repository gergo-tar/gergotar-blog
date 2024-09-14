<?php

namespace Domain\Category\Observers;

use Domain\Category\Models\CategoryTranslation;

// See events: https://laravel.com/docs/11.x/eloquent#events
class CategoryTranslationObserver
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(CategoryTranslation $model): void
    {
        // Update Category updated_at timestamp
        $model->category->touch();
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(CategoryTranslation $model): void
    {
        // Update Category updated_at timestamp
        $model->category->touch();
    }
}
