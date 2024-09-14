<?php

namespace Domain\Project\QueryBuilders;

use Domain\Shared\Traits\HasWhereActiveBuilder;
use Domain\Translation\QueryBuilders\AbstractHasTranslationsQueryBuilder;

class ProjectQueryBuilder extends AbstractHasTranslationsQueryBuilder
{
    use HasWhereActiveBuilder;
}
