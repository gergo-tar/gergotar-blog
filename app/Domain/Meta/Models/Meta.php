<?php

namespace Domain\Meta\Models;

use Domain\Abstract\Models\BaseModel;
use Database\Factories\Domain\Meta\MetaFactory;
use Domain\Meta\QueryBuilders\MetaQueryBuilder;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Meta extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
