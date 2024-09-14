<?php

namespace Domain\Form\QueryBuilders;

use Illuminate\Database\Eloquent\Builder;
use Domain\Shared\Traits\HasWhereActiveBuilder;

class FormQueryBuilder extends Builder
{
    use HasWhereActiveBuilder;
}
