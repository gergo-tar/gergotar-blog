<?php

namespace Database\Factories\Domain\Blog;

use Database\Factories\Domain\User\UserFactory;
use Domain\Blog\Models\PostAuthor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Blog\Models\PostAuthor>
 */
class PostAuthorFactory extends UserFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = PostAuthor::class;
}
