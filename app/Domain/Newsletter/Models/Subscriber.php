<?php

namespace Domain\Newsletter\Models;

use Database\Factories\Domain\Newsletter\SubscriberFactory;
use Domain\Abstract\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Subscriber extends BaseModel
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'name',
        'preferred_language',
    ];

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): SubscriberFactory
    {
        return SubscriberFactory::new();
    }
}
