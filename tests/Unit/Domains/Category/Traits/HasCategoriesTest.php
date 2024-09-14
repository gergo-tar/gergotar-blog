<?php

use Domain\Category\Models\Category;
use Domain\Category\Traits\HasCategories;
use Illuminate\Database\Eloquent\Model;

test('has-categories-trait', function () {
    // Create a class on the fly that uses the HasCategories trait.
    $model = new class () extends Model {
        use HasCategories;
    };
    $model->id = 1;

    $count = 3;
    $categories = Category::factory($count)->create();
    $model->categories()->attach($categories);

    // Check if the model has the right categories.
    expect($model->categories->count())->toBe($count);
    expect($model->categories->first()->name)->toBe($categories->first()->name);
    expect($model->categories->last()->name)->toBe($categories->last()->name);
    expect($model->categories->first())->toBeInstanceOf(Category::class);
});
