<?php

namespace Domain\Shared\Traits;

trait GetTableName
{
    /**
     * Get the table name of the model.
     */
    public static function getTableName(): string
    {
        return static::query()->getModel()->getTable();
    }
}
