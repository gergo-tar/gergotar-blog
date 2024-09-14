<?php

namespace Domain\Project\Observers;

use Domain\Project\Models\ProjectTranslation;

// See events: https://laravel.com/docs/11.x/eloquent#events
class ProjectTranslationObserver
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(ProjectTranslation $model): void
    {
        // Update Project updated_at timestamp
        $model->project->touch();
    }

    /**
     * Handle the Model "updating" event.
     */
    public function updating(ProjectTranslation $model): void
    {
        // Update Project updated_at timestamp
        $model->project->touch();
    }
}
