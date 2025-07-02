<?php

use Rector\Config\RectorConfig;
use RectorLaravel\Set\LaravelLevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->sets([
        LaravelLevelSetList::UP_TO_LARAVEL_120,
    ]);
};
