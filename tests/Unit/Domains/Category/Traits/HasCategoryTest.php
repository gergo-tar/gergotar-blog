<?php

use Domain\Category\Models\Category;
use Domain\Category\Traits\HasCategory;
use Illuminate\Database\Eloquent\Model;
use Domain\Category\Models\CategoryTranslation;

test('has-category-trait', function () {
    // Create a class on the fly that uses the HasCategory trait.
    $model = new class () extends Model {
        use HasCategory;
    };
    $model->id = 1;

    $category = Category::factory()->hasSupportedTranslations()->create();
    $model->categories()->attach($category);

    // Check if the model has the right category.
    $model->loadCategory();

    expect($model->relationLoaded('category'))->toBeTrue();
    expect($model->category->name)->toBe($category->name);
    expect($model->category)->toBeInstanceOf(Category::class);
    expect($model->category->relationLoaded('translation'))->toBeTrue();
    expect($model->category->translation)->toBeInstanceOf(CategoryTranslation::class);
    expect($model->category->translation->name)->toBe($category->translation->name);
});
