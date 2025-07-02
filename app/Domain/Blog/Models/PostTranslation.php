<?php

namespace Domain\Blog\Models;

use Spatie\Sluggable\HasSlug;
use Domain\Meta\Traits\HasMetas;
use Spatie\Sluggable\SlugOptions;
use Domain\User\Traits\HasUserAudit;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Domain\Blog\Observers\PostTranslationObserver;
use Domain\Translation\Models\AbstractTranslation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Database\Factories\Domain\Blog\PostTranslationFactory;

/**
 * @property int $id Primary key
 * @property string $locale The locale of the translation
 * @property string $title The title of the post in the specified locale
 * @property string|null $slug The slug for the post, generated from the title
 * @property string|null $content The content of the post in the specified locale
 * @property string|null $toc Table of contents for the post in the specified locale
 * @property string|null $excerpt A brief excerpt of the post in the specified locale
 * @property string|null $url The canonical URL for the post, generated from the slug
 * @property array|null $reading_time Estimated reading time for the post in minutes and seconds
 * @property Post|null $post The post that owns this translation
 */
#[ObservedBy([PostTranslationObserver::class])]
class PostTranslation extends AbstractTranslation
{
    use HasMetas;
    use HasSlug;
    use HasUserAudit;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'locale',
        'title',
        'slug',
        'content',
        'toc',
        'excerpt',
        'reading_time',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reading_time' => 'array',
        ];
    }

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
    protected function url(): Attribute
    {
        return Attribute::make(get: function () {
            return route('blog.posts.show', [
                'slug' => $this->slug,
            ]);
        });
    }

    /**
     * Get the reading time string attribute.
     */
    protected function readingTimeString(): Attribute
    {
        return Attribute::make(get: function () {
            $readingTime = $this->reading_time && !empty($this->reading_time)
                ? $this->reading_time
                : [];
            $minutes = $readingTime['minutes'] ?? 1;
            $seconds = $readingTime['seconds'] ?? 0;
            // if the reading time is less than 1 minute, then show the seconds.
            if ($minutes == 0) {
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
        });
    }
}
