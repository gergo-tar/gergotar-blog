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
        $this->estimateReadingTime($model);
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(PostTranslation $model): void
    {
        $this->estimateReadingTime($model);
    }

    /**
     * Handle the Model "updated" event.
     */
    private function estimateReadingTime(PostTranslation $model): void
    {
        $model->reading_time = EstimatePostReadingTime::run(
            is_array($model->content) ? tiptap_converter()->asText($model->content) : $model->content
        );
        $model->post->touch();
    }
}
