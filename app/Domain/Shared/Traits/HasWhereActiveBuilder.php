<?php

namespace Domain\Shared\Traits;

trait HasWhereActiveBuilder
{
    /**
     * Get active models.
     */
    public function whereActive(): self
    {
        return $this->where('is_active', true);
    }

    /**
     * Get inactive models.
     */
    public function whereInactive(): self
    {
        return $this->where('is_active', false);
    }
}
