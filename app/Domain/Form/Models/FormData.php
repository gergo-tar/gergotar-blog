<?php

namespace Domain\Form\Models;

use Domain\Abstract\Models\BaseModel;
use Database\Factories\Domain\Form\FormDataFactory;

/**
 * @property int $id Primary key
 * @property array $data The form data
 * @property string $language The language of the form submission
 * @property string $ip_address The IP address of the user
 * @property string $user_agent The user agent string of the user's device
 * @property string $country The country of the user
 * @property string $city The city of the user
 * @property string $region The region of the user
 * @property string $postal_code The postal code of the user
 * @property float $latitude The latitude of the user's location
 * @property float $longitude The longitude of the user's location
 * @property string $timezone The timezone of the user
 * @property Form|null $form The form that owns this form data
 */
class FormData extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'data',
        'language',
        'ip_address',
        'user_agent',
        'country',
        'city',
        'region',
        'postal_code',
        'latitude',
        'longitude',
        'timezone',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'data' => 'array',
        ];
    }

    /**
     * Create a new factory instance for the model.
     */
    protected static function newFactory(): FormDataFactory
    {
        return FormDataFactory::new();
    }

    /**
     * Get the form that owns the form data.
     */
    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
