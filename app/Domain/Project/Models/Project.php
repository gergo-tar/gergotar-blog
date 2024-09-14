<?php

namespace Domain\Project\Models;

use Illuminate\Support\Collection;
use Domain\Abstract\Models\BaseModel;
use Domain\Translation\Traits\HasTranslations;
use Database\Factories\Domain\Project\ProjectFactory;
use Domain\Project\QueryBuilders\ProjectQueryBuilder;

/**
 * @property ProjectTranslation|null $translation
 * @property Collection<ProjectTranslation|null> $translations
 */
class Project extends BaseModel
{
    use HasTranslations;

    /**
     * The cache key for the model.
     */
    public const CACHE_KEY = 'projects';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'url',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProjectFactory
    {
        return ProjectFactory::new();
    }

    /**
     * Get the query builder for the model.
     */
    public function newEloquentBuilder($query): ProjectQueryBuilder
    {
        return new ProjectQueryBuilder($query);
    }
}
