<?php

namespace Domain\Form\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Domain\Abstract\Models\BaseModel;
use Database\Factories\Domain\Form\FormFactory;
use Domain\Form\QueryBuilders\FormQueryBuilder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Form extends BaseModel
{
    use HasSlug;

    /**
     * The cache key for the model.
     */
    public const CACHE_KEY = 'forms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'fields',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fields' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): FormFactory
    {
        return FormFactory::new();
    }

    /**
     * Get the query builder for the model.
     */
    public function newEloquentBuilder($query): FormQueryBuilder
    {
        return new FormQueryBuilder($query);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the FormData for the model.
     */
    public function formData(): HasMany
    {
        return $this->hasMany(FormData::class);
    }
}
