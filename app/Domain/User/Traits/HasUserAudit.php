<?php

namespace Domain\User\Traits;

use Domain\User\Models\User;
use Domain\User\Observers\UserAuditObserver;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasUserAudit
{
    public static function bootHasUserAudit(): void
    {
        // Register the observer directly for the model using observe method
        static::observe(UserAuditObserver::class);
    }

    /**
     * Get the user who created the model.
     */
    public function createdBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /**
     * Get the user who updated the model.
     */
    public function updatedBy(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'updated_by');
    }
}
