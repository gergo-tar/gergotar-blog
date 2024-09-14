<?php

namespace Domain\Tag\Observers;

use Domain\Tag\Models\TagTranslation;

// See events: https://laravel.com/docs/11.x/eloquent#events
class TagTranslationObserver
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(TagTranslation $model): void
    {
        // Update Tag updated_at timestamp
        $model->tag->touch();
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(TagTranslation $model): void
    {
        // Update Tag updated_at timestamp
        $model->tag->touch();
    }
}
