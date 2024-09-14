<?php

use Domain\Blog\Models\PostAuthor;
use Domain\Blog\Traits\HasPostAuthor;
use Illuminate\Database\Eloquent\Model;

test('has-post-author-trait', function () {
    // Create a class on the fly that uses the HasPostAuthor trait.
    $model = new class () extends Model {
        use HasPostAuthor;
    };
    $model->id = 1;

    $author = PostAuthor::factory()->create();
    $model->author_id = $author->id;

    // Load the author relationship.
    $model->loadAuthor();

    // Check if the model has the right author.
    expect($model->relationLoaded('author'))->toBeTrue();
    expect($model->author->id)->toBe($author->id);
    expect($model->author->name)->toBe($author->name);
    expect($model->author)->toBeInstanceOf(PostAuthor::class);
});
