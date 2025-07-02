<?php

namespace Domain\Project\Models;

use Illuminate\Support\Collection;
use Domain\Abstract\Models\BaseModel;
use Domain\Translation\Traits\HasTranslations;
use Database\Factories\Domain\Project\ProjectFactory;
use Domain\Project\QueryBuilders\ProjectQueryBuilder;

/**
 * @property int $id Primary key
 * @property string $url The URL of the project
 * @property bool $is_active Indicates if the project is active
 * @property ProjectTranslation|null $translation  Default or selected translation for the project
 * @property Collection<ProjectTranslation|null> $translations  Translations for the project
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
     * @var list<string>
     */
    protected $fillable = [
        'url',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

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
