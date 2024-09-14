<?php

use Domain\Shared\Traits\GetTableName;
use Illuminate\Database\Eloquent\Model;

test('get-table-name-trait', function () {
    // Create a class on the fly that uses the HasCaGetTableName trait.
    $model = new class () extends Model {
        use GetTableName;
    };

    expect($model::getTableName())->toBe($model->getTable());
});
