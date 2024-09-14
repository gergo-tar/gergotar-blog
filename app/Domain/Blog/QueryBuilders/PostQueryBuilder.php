<?php

namespace Domain\Blog\QueryBuilders;

use Domain\Translation\QueryBuilders\AbstractHasTranslationsQueryBuilder;

class PostQueryBuilder extends AbstractHasTranslationsQueryBuilder
{
    /**
     * Order by published at.
     *
     * @param  string  $direction  The direction to order by.
     */
    public function orderByPublishedAt(string $direction = 'desc'): self
    {
        if (! in_array($direction, ['asc', 'desc'])) {
            $direction = 'desc';
        }

        return $this->orderBy('published_at', $direction);
    }

    /**
     * Get published posts.
     */
    public function wherePublished(): self
    {
        return $this->where('is_published', true);
    }

    /**
     * Get unpublished posts.
     */
    public function whereUnpublished(): self
    {
        return $this->where('is_published', false);
    }

    /**
     * Get post with category.
     */
    public function withCategory(): self
    {
        return $this->with([
            'category' => function ($query) {
                $table = $query->getModel()->getTable();
                $query->select("{$table}.id");
            },
            'category.translation' => function ($query) {
                $table = $query->getModel()->getTable();
                $query->select("{$table}.category_id", "{$table}.name", "{$table}.slug", "{$table}.locale");
            },
        ]);
    }

    /**
     * Get post with Featured Image.
     */
    public function withFeaturedImage(array $columns = ['*']): self
    {
        return $this->with([
            'featuredImage' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }

    /**
     * Get post with translations.
     *
     * @param  string|null  $slug  The post slug.
     * @param  array  $columns  The columns to select.
     */
    public function withTranslations(array $columns = ['*'], ?string $slug = null): self
    {
        return $this->with([
            'translations' => function ($query) use ($columns, $slug) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns))
                    ->when($slug, fn ($query) => $query->whereSlug($slug));
            },
        ]);
    }

    /**
     * Get post with translations.
     *
     * @param  array  $columns  The columns to select.
     */
    public function withTranslationsMetas(array $columns = ['*']): self
    {
        return $this->with([
            'translations.metas' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }
}
