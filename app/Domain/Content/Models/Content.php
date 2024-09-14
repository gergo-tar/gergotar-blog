<?php

namespace Domain\Content\Models;

use Spatie\Sluggable\HasSlug;
use Domain\Meta\Traits\HasMetas;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Support\Collection;
use Domain\Abstract\Models\BaseModel;
use Domain\Translation\Traits\HasTranslations;
use Database\Factories\Domain\Content\ContentFactory;
use Domain\Content\QueryBuilders\ContentQueryBuilder;

/**
 * @property ContentTranslation|null $translation
 * @property Collection<ContentTranslation|null> $translations
 */
class Content extends BaseModel
{
    use HasMetas;
    use HasSlug;
    use HasTranslations;

    /**
     * The cache key for the model.
     */
    public const CACHE_KEY = 'contents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];

    /**
     * Get the query builder for the model.
     */
    public function newEloquentBuilder($query): ContentQueryBuilder
    {
        return new ContentQueryBuilder($query);
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ContentFactory
    {
        return ContentFactory::new();
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }
}
