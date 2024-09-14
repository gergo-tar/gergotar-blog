<?php

namespace Domain\Form\Models;

use Domain\Abstract\Models\BaseModel;
use Database\Factories\Domain\Form\FormDataFactory;

class FormData extends BaseModel
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
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
     * @var array<string, string>
     */
    protected $casts = [
        'data' => 'array',
    ];

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
