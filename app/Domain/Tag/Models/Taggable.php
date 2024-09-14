<?php

namespace Domain\Tag\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Taggable extends Model
{
    /**
     * Get all of the models that own tags.
     */
    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
