<?php

namespace Domain\Tag\Models;

use Domain\Blog\Models\Post;
use Illuminate\Support\Collection;
use Domain\Abstract\Models\BaseModel;
use Database\Factories\Domain\Tag\TagFactory;
use Domain\Tag\QueryBuilders\TagQueryBuilder;
use Domain\Translation\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property TagTranslation|null $translation
 * @property Collection<TagTranslation|null> $translations
 */
class Tag extends BaseModel
{
    use HasTranslations;

    /**
     * The cache key for the model.
     */
    public const CACHE_KEY = 'tags';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): TagFactory
    {
        return TagFactory::new();
    }

    /**
     * Get the query builder for the model.
     */
    public function newEloquentBuilder($query): TagQueryBuilder
    {
        return new TagQueryBuilder($query);
    }

    /**
     * Get the posts for the model.
     */
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(
            Post::class,
            'taggable',
            'taggables',
            'tag_id',
            'taggable_id'
        )
            ->where('taggable_type', Post::class)
            ->withTimestamps();
    }
}
