<?php

namespace Domain\Blog\Models;

use Database\Factories\Domain\Blog\PostAuthorFactory;
use Domain\User\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostAuthor extends User
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): PostAuthorFactory
    {
        return PostAuthorFactory::new();
    }

    /**
     * Get the posts for the author.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'author_id', 'id');
    }
}
