<?php

namespace Database\Factories\Domain\Meta;

use Domain\Meta\Models\Meta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Meta\Models\Meta>
 */
class MetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Meta::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->word,
            'value' => $this->faker->sentence(1),
        ];
    }

    /**
     * Associate the Meta with a given model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @return $this
     */
    public function forMetable(Model $model): self
    {
        return $this->state(fn () => [
            'metable_id'   => $model->getKey(),
            'metable_type' => get_class($model),
        ]);
    }
}
