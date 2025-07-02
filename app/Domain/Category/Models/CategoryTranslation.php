<?php

namespace Domain\Category\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Domain\Translation\Models\AbstractTranslation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Domain\Category\Observers\CategoryTranslationObserver;
use Database\Factories\Domain\Category\CategoryTranslationFactory;

/**
 * @property int $id Primary key
 * @property string $locale The locale of the translation
 * @property string $name The name of the category in the specified locale
 * @property string|null $slug The slug for the category, generated from the name
 * @property string|null $description A description of the category in the specified locale
 * @property Category|null $category The category that owns this translation
 */
#[ObservedBy([CategoryTranslationObserver::class])]
class CategoryTranslation extends AbstractTranslation
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'locale',
        'name',
        'slug',
        'description',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): CategoryTranslationFactory
    {
        return CategoryTranslationFactory::new();
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
     * Get the category that owns the translation.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
