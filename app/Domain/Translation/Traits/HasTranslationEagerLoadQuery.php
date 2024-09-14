<?php

namespace Domain\Translation\Traits;

trait HasTranslationEagerLoadQuery
{
    /**
     * Get Multiple Translation eager load.
     *
     * @param  array  $columns  The columns to load.
     */
    public function getMultipleTranslationEagerLoadQuery(array $columns = ['*']): array
    {
        return $this->getTranslationEagerLoadQuery('translations', $columns);
    }

    /**
     * Get Single Translation eager load.
     *
     * @param  array  $columns  The columns to load.
     */
    public function getSingleTranslationEagerLoadQuery(array $columns = ['*']): array
    {
        return $this->getTranslationEagerLoadQuery('translation', $columns);
    }

    /**
     * Get Translation eager load columns query.
     *
     * @param  string  $relation  The relation to load.
     * @param  array  $columns  The columns to load.
     */
    private function getTranslationEagerLoadQuery(string $relation, array $columns = ['*']): array
    {
        return [
            $relation => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            }
        ];
    }
}
