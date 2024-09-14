<?php

namespace Domain\Category\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Categoriable extends Model
{
    /**
     * Get all of the models that own categories.
     */
    public function categoriable(): MorphTo
    {
        return $this->morphTo();
    }
}
