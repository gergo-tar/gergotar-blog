<?php

namespace Domain\Newsletter\Models;

use Database\Factories\Domain\Newsletter\SubscriberFactory;
use Domain\Abstract\Models\BaseModel;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

/**
 * @property string $id Unique identifier for the subscriber
 * @property string $email Email address of the subscriber
 * @property string|null $name Name of the subscriber
 * @property string|null $preferred_language Preferred language of the subscriber
 */
class Subscriber extends BaseModel
{
    use HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
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
