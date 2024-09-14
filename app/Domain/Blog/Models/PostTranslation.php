<?php

namespace Domain\Blog\Models;

use Spatie\Sluggable\HasSlug;
use Domain\Meta\Traits\HasMetas;
use Spatie\Sluggable\SlugOptions;
use Domain\User\Traits\HasUserAudit;
use Domain\Blog\Observers\PostTranslationObserver;
use Domain\Translation\Models\AbstractTranslation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Database\Factories\Domain\Blog\PostTranslationFactory;

#[ObservedBy([PostTranslationObserver::class])]
class PostTranslation extends AbstractTranslation
{
    use HasMetas;
    use HasSlug;
    use HasUserAudit;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'locale',
        'title',
        'slug',
        'content',
        'excerpt',
        'reading_time',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reading_time' => 'array',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): PostTranslationFactory
    {
        return PostTranslationFactory::new();
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
     * Get the post that owns the translation.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the canonical URL for the post.
     */
    public function getUrlAttribute(): string
    {
        return route('blog.posts.show', [
            'slug' => $this->slug,
        ]);
    }

    /**
     * Get the reading time string attribute.
     */
    public function getReadingTimeStringAttribute(): string
    {
        $minutes = $this->reading_time['minutes'] ?? 1;
        $seconds = $this->reading_time['seconds'] ?? 0;

        // if the reading time is less than 1 minute, then show the seconds.
        if ($minutes === 0) {
            return __('Blog::post.translations.reading_time_in_seconds', [
                'sec' => $seconds,
            ]);
        }

        // If reading time is more than 1 minute and seconds are more than 30, then show the minutes + 1.
        if ($seconds > 30) {
            $minutes += 1;
        }

        return __('Blog::post.translations.reading_time_in_minutes', [
            'min' => $minutes,
        ]);
    }
}
