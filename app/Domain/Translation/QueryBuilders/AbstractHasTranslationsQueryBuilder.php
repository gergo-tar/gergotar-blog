<?php

namespace Domain\Translation\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Domain\Translation\Traits\WhereTranslation;
use Domain\Translation\Traits\HasTranslationEagerLoadQuery;

abstract class AbstractHasTranslationsQueryBuilder extends Builder
{
    use HasTranslationEagerLoadQuery;
    use WhereTranslation;

    /**
     * Get Model with locale translation.
     *
     * @param  array  $columns  The columns to select.
     */
    public function withTranslation(array $columns = ['*']): self
    {
        return $this->with($this->getSingleTranslationEagerLoadQuery($columns));
    }

    /**
     * Get Model with multiple locale translations.
     *
     * @param  array  $columns  The columns to select.
     */
    public function withTranslations(array $columns = ['*']): self
    {
        return $this->with($this->getMultipleTranslationEagerLoadQuery($columns));
    }
}
