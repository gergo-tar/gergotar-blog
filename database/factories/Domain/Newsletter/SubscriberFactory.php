<?php

namespace Database\Factories\Domain\Newsletter;

use Domain\Newsletter\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Newsletter\Models\Subscriber>
 */
class SubscriberFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Subscriber::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $languages = config('localized-routes.supported_locales');

        return [
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->name,
            'preferred_language' => $languages[array_rand($languages)],
        ];
    }
}
