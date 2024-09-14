<?php

namespace Domain\Translation\Traits;

trait WhereTranslation
{
    /**
     * Get Model by its translation name.
     *
     * @param  string  $name  The name of the translation.
     */
    public function whereTranslationName(string $name): self
    {
        return $this->whereTranslation('name', $name);
    }

    /**
     * Get Model by its translation slug.
     *
     * @param  string  $slug  The slug of the translation.
     */
    public function whereTranslationSlug(string $slug): self
    {
        return $this->whereTranslation('slug', $slug);
    }

    /**
     * Filter the model by its translation column.
     *
     * @param  string  $column  The column to filter.
     * @param  string  $value  The value to filter.
     * @param  string|null  $operator  The operator to use.
     */
    public function whereTranslation(string $column, string $value, ?string $operator = null): self
    {
        $operator = $operator ?: '=';
        return $this->whereHas('translations', function ($query) use ($column, $operator, $value) {
            $query->select('id', 'locale')->where($column, $operator, $value);
        });
    }
}
