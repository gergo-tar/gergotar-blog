<?php

namespace Domain\Content\Observers;

use Domain\Content\Models\ContentTranslation;

// See events: https://laravel.com/docs/11.x/eloquent#events
class ContentTranslationObserver
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(ContentTranslation $model): void
    {
        // Update Content updated_at timestamp
        if ($model->contentModel) {
            $model->contentModel->touch();
        }
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(ContentTranslation $model): void
    {
        // Update Content updated_at timestamp
        $model->contentModel->touch();
    }
}
