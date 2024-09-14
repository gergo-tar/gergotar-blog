<?php

namespace Domain\User\Observers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;

// See events: https://laravel.com/docs/11.x/eloquent#events
class UserAuditObserver
{
    /**
     * Handle the Model "creating" event.
     */
    public function creating(Model $model): void
    {
        if (auth()->id()) {
            // Check if model has created_by and updated_by columns
            if (Schema::hasColumn($model->getTable(), 'created_by')) {
                $model->setAttribute('created_by', auth()->id());
            }
            if (Schema::hasColumn($model->getTable(), 'updated_by')) {
                $model->setAttribute('updated_by', auth()->id());
            }
        }
    }

    /**
     * Handle the Model "updated" event.
     */
    public function updated(Model $model): void
    {
        if (auth()->id() && Schema::hasColumn($model->getTable(), 'updated_by')) {
            $model->setAttribute('updated_by', auth()->id());
        }
    }
}
