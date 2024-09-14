<?php

namespace Domain\Category\Models;

use Domain\Blog\Models\Post;
use Illuminate\Support\Collection;
use Domain\Abstract\Models\BaseModel;
use Domain\Translation\Traits\HasTranslations;
use Database\Factories\Domain\Category\CategoryFactory;
use Domain\Category\QueryBuilders\CategoryQueryBuilder;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

/**
 * @property CategoryTranslation|null $translation
 * @property Collection<CategoryTranslation|null> $translations
 */
class Category extends BaseModel
{
    use HasTranslations;

    /**
     * The cache key for the model.
     */
    public const CACHE_KEY = 'categories';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CategoryFactory
    {
        return CategoryFactory::new();
    }

    /**
     * Get the query builder for the model.
     */
    public function newEloquentBuilder($query): CategoryQueryBuilder
    {
        return new CategoryQueryBuilder($query);
    }

    /**
     * Get the posts for the model.
     */
    public function posts(): MorphToMany
    {
        return $this->morphedByMany(
            Post::class,
            'categoriable',
            'categoriables',
            'category_id',
            'categoriable_id'
        )
            ->where('categoriable_type', Post::class)
            ->withTimestamps();
    }
}
