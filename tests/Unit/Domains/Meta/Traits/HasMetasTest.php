<?php

use Domain\Meta\Models\Meta;
use Domain\Meta\Traits\HasMetas;
use Illuminate\Database\Eloquent\Model;

test('has-metas-trait', function () {
    // Create a class on the fly that uses the HasMetas trait.
    $model = new class () extends Model {
        use HasMetas;
    };
    $model->id = 1;

    $count = 3;
    $metas = Meta::factory($count)->forMetable($model)->create();

    // Check if the model has the right metas.
    $model->loadMetas();

    expect($model->relationLoaded('metas'))->toBeTrue();

    expect($model->metas->count())->toBe($count);
    expect($model->metas->first()->key)->toBe($metas->first()->key);
    expect($model->metas->last()->value)->toBe($metas->last()->value);
    expect($model->metas->first())->toBeInstanceOf(Meta::class);
});
