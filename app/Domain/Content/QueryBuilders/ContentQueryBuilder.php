<?php

namespace Domain\Content\QueryBuilders;

use Domain\Translation\QueryBuilders\AbstractHasTranslationsQueryBuilder;

class ContentQueryBuilder extends AbstractHasTranslationsQueryBuilder
{
    /**
     * Get Model with translation metas.
     *
     * @param  array  $columns  The columns to select.
     */
    public function withTranslationMetas(array $columns = ['*']): self
    {
        return $this->with([
            'translation.metas' => function ($query) use ($columns) {
                $query->select(prefix_table_columns(table: $query->getModel()->getTable(), columns: $columns));
            },
        ]);
    }
}
