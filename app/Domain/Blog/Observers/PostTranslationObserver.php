<?php

namespace Domain\Blog\Observers;

use Domain\Blog\Actions\EstimatePostReadingTime;
use Domain\Blog\Models\PostTranslation;

// See events: https://laravel.com/docs/11.x/eloquent#events
class PostTranslationObserver
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(PostTranslation $model): void
    {
        // Calculate the reading time
        $model->reading_time = EstimatePostReadingTime::run(tiptap_converter()->asText($model->content));
        // Update Post updated_at timestamp
        $model->post->touch();
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(PostTranslation $model): void
    {
        // Calculate the reading time
        $model->reading_time = EstimatePostReadingTime::run(tiptap_converter()->asText($model->content));
        // Update Post updated_at timestamp
        $model->post->touch();
    }
}
