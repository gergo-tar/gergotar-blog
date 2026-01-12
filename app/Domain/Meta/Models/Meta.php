<?php

namespace Domain\Meta\Models;

use Illuminate\Database\Eloquent\Model;
use Domain\Abstract\Models\BaseModel;
use Database\Factories\Domain\Meta\MetaFactory;
use Domain\Meta\QueryBuilders\MetaQueryBuilder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $key The key of the meta data
 * @property string $type The type of the meta data (e.g., 'string', 'json')
 * @property mixed $value The value of the meta data
 * @property string $metable_type The type of the parent model (e.g., 'Post', 'Page')
 * @property-read Model $metable The parent model that owns this meta data
 */
class Meta extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'key',
        'type',
        'value',
        'metable_type',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): MetaFactory
    {
        return MetaFactory::new();
    }

    /**
     * Get the query builder for the model.
     */
    public function newEloquentBuilder($query): MetaQueryBuilder
    {
        return new MetaQueryBuilder($query);
    }

    /**
     * Get the parent metable model (post, page, etc.).
     */
    public function metable(): MorphTo
    {
        return $this->morphTo();
    }
}
