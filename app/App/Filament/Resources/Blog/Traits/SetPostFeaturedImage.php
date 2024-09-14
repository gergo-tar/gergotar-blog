<?php

namespace App\Filament\Resources\Blog\Traits;

use Domain\Blog\Models\Post;

trait SetPostFeaturedImage
{
    /**
     * Set default data values.
     *
     * @param  Post  $record  The Post record.
     */
    public function setPostFeaturedImage(Post $record): array
    {
        $featuredImage = $record->featuredImage;

        if (! $featuredImage) {
            return [];
        }

        return [$featuredImage->toArray()];
    }
}
