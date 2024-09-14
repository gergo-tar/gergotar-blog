<?php

namespace Database\Factories\Domain\Form;

use Domain\Form\Models\FormData;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Form\Models\FormData>
 */
class FormDataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = FormData::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'data' => [
                'name' => $this->faker->name,
                'email' => $this->faker->unique()->safeEmail,
                'message' => $this->faker->sentence,
            ],
            'ip_address' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent,
            'country' => $this->faker->country,
            'city' => $this->faker->city,
            'region' => $this->faker->state,
            'postal_code' => $this->faker->postcode,
            'latitude' => $this->faker->latitude,
            'longitude' => $this->faker->longitude,
            'timezone' => $this->faker->timezone,
        ];
    }
}
