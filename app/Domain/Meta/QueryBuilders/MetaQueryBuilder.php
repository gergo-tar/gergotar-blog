<?php

namespace Domain\Meta\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;

class MetaQueryBuilder extends Builder
{
    /**
     * Filter out metas with the given keys.
     */
    public function whereNotHasKey(array $keys): self
    {
        return $this->whereNotIn('key', $keys);
    }
}
