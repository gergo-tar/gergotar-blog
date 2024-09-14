<?php

use Awcodes\Curator\Models\Media;
use Illuminate\Database\Eloquent\Model;
use Domain\Blog\Traits\HasPostFeaturedImage;

test('has-post-feature-image-trait', function () {
    // Create a class on the fly that uses the HasPostFeaturedImage trait.
    $model = new class () extends Model {
        use HasPostFeaturedImage;
    };
    $model->id = 1;

    // Create a media model.
    $media = Media::factory()->create();
    $model->featured_image_id = $media->id;

    // Load the featured image relationship.
    $model->loadFeaturedImage();

    // Check if the model has the right featured image.
    expect($model->relationLoaded('featuredImage'))->toBeTrue();
    expect($model->featuredImage->id)->toBe($media->id);
    expect($model->featuredImage->path)->toBe($media->path);
    expect($model->featuredImage)->toBeInstanceOf(Media::class);
});

test('get-featured-image-alt-attribute', function () {
    // Create a class on the fly that uses the HasPostFeaturedImage trait.
    $model = new class () extends Model {
        use HasPostFeaturedImage;
    };
    $model->id = 1;

    // Create a media model.
    $media = Media::factory()->create();
    $model->featured_image_id = $media->id;

    // Load the featured image relationship.
    $model->loadFeaturedImage();

    // Check if the model has the right featured image alt text.
    expect($model->featuredImageAlt)->toBe($media->alt);
});

test('get-featured-image-url-attribute', function () {
    // Create a class on the fly that uses the HasPostFeaturedImage trait.
    $model = new class () extends Model {
        use HasPostFeaturedImage;
    };
    $model->id = 1;

    // Create a media model.
    $media = Media::factory()->create();
    $model->featured_image_id = $media->id;

    // Load the featured image relationship.
    $model->loadFeaturedImage();

    // Check if the model has the right featured image URL.
    expect($model->featuredImageUrl)->toBe(asset('storage/' . $media->path));
});
