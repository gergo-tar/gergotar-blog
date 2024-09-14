<?php

namespace Domain\Blog\Models;

use Carbon\Carbon;
use Spatie\Sitemap\Tags\Url;
use Domain\Tag\Traits\HasTags;
use Domain\Meta\Traits\HasMetas;
use Illuminate\Support\Collection;
use Domain\User\Traits\HasUserAudit;
use Domain\Abstract\Models\BaseModel;
use Domain\Blog\Traits\HasPostAuthor;
use Domain\Category\Traits\HasCategory;
use Spatie\Sitemap\Contracts\Sitemapable;
use Domain\Blog\Traits\HasPostFeaturedImage;
use Domain\Translation\Traits\HasTranslations;
use Database\Factories\Domain\Blog\PostFactory;
use Domain\Blog\QueryBuilders\PostQueryBuilder;

/**
 * @property PostTranslation|null $translation
 * @property Collection<PostTranslation|null> $translations
 */
class Post extends BaseModel implements Sitemapable
{
    use HasCategory;
    use HasMetas;
    use HasPostAuthor;
    use HasPostFeaturedImage;
    use HasTags;
    use HasTranslations;
    use HasUserAudit;

    /**
     * The cache key for the model.
     */
    public const CACHE_KEY = 'posts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'author_id',
        'featured_image_id',
        'is_published',
        'published_at',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): PostFactory
    {
        return PostFactory::new();
    }

    /**
     * Get the query builder for the model.
     */
    public function newEloquentBuilder($query): PostQueryBuilder
    {
        return new PostQueryBuilder($query);
    }

    /**
     * Get the sitemap tag for the model.
     */
    public function toSitemapTag(): Url | string | array
    {
        return Url::create($this->translation->url)
            ->setLastModificationDate(Carbon::create($this->updated_at))
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
            ->setPriority(0.1);
    }

    /**
     * Load relationships.
     */
    public function loadRelationships(): void
    {
        $this
            ->loadAuthor(['id', 'name'])
            ->loadCategory(['category_id', 'name', 'slug', 'locale'])
            ->loadFeaturedImage(['id', 'path', 'width', 'height', 'alt', 'disk'])
            ->loadTags(['tag_id', 'name', 'slug', 'locale'])
            ->loadTranslation(['post_id', 'locale', 'title', 'slug', 'content', 'excerpt', 'reading_time']);
    }

    /**
     * Load the translations metas.
     *
     * @param  array<string>  $columns
     * @return $this
     */
    public function loadTranslationsMetas(array $columns = ['*']): self
    {
        return $this->load([
            'translations.metas' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }

    /**
     * Get the post's published at date in a formatted way.
     * Based on the selected locale.
     */
    public function getPublishedAtFormattedAttribute(): string
    {
        if (! $this->published_at) {
            return '';
        }

        $format = 'l, F j, Y';
        // Different format for Hungarian locale.
        if (app()->getLocale() === 'hu') {
            $format = 'Y. F j., l';
        }

        return $this->published_at->translatedFormat($format);
    }

    /**
     * Get the post's tags names.
     */
    public function getTagsNamesAttribute(): array
    {
        return $this->tags->pluck('translation.name')->toArray();
    }
}
