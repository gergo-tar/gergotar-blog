<?php

namespace Domain\Project\Models;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Domain\Translation\Models\AbstractTranslation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Domain\Project\Observers\ProjectTranslationObserver;
use Database\Factories\Domain\Project\ProjectTranslationFactory;

/**
 * @property int $id Primary key
 * @property string $locale The locale of the translation
 * @property string $title The title of the project in the specified locale
 * @property string|null $slug The slug for the project, generated from the title
 * @property string|null $description A description of the project in the specified locale
 * @property Project|null $project The project that owns this translation
 */
#[ObservedBy([ProjectTranslationObserver::class])]
class ProjectTranslation extends AbstractTranslation
{
    use HasSlug;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'locale',
        'title',
        'slug',
        'description',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): ProjectTranslationFactory
    {
        return ProjectTranslationFactory::new();
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

    /**
     * Get the project that owns the translation.
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
}
