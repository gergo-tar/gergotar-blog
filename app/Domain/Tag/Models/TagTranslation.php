<?php

namespace Domain\Tag\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Domain\Tag\Observers\TagTranslationObserver;
use Domain\Translation\Models\AbstractTranslation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Database\Factories\Domain\Tag\TagTranslationFactory;

/**
 * @property int $id Primary key
 * @property string $locale The locale of the translation
 * @property string $name The name of the tag in the specified locale
 * @property string|null $slug The slug for the tag, generated from the name
 * @property string|null $description A description of the tag in the specified locale
 * @property Tag|null $tag The tag that owns this translation
 */
#[ObservedBy([TagTranslationObserver::class])]
class TagTranslation extends AbstractTranslation
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
    protected static function newFactory(): TagTranslationFactory
    {
        return TagTranslationFactory::new();
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
     * Get the tag that owns the translation.
     */
    public function tag(): BelongsTo
    {
        return $this->belongsTo(Tag::class);
    }
}
