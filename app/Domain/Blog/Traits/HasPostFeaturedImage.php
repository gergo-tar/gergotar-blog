<?php

namespace Domain\Blog\Traits;

use Domain\Media\Models\Media;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasPostFeaturedImage
{
    /**
     * Get the featured image of the post.
     */
    public function featuredImage(): BelongsTo
    {
        return $this->belongsTo(Media::class, 'featured_image_id');
    }

    /**
     * Get the featured image alt text.
     */
    public function getFeaturedImageAltAttribute(): string
    {
        return $this->featuredImage?->alt ?? $this->translation->title;
    }

    /**
     * Get the featured image URL.
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        $featuredImage = $this->featuredImage;
        if (! $featuredImage) {
            return null;
        }

        return asset('storage/' . $featuredImage->path);
    }

    /**
     * Load the featured image relationship.
     *
     * @param  array<string>  $columns
     * @return $this
     */
    public function loadFeaturedImage(array $columns = ['*']): self
    {
        return $this->load([
            'featuredImage' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }
}
