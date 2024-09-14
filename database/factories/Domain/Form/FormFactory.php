<?php

namespace Database\Factories\Domain\Form;

use Illuminate\Support\Str;
use Domain\Form\Models\Form;
use Database\Factories\Traits\FactoryHasActive;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Form\Models\Form>
 */
class FormFactory extends Factory
{
    use FactoryHasActive;

    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Form::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(2);
        return [
            'name' => $name,
            'slug' => Str::slug($name),
            'fields' => ['name', 'email', 'message'],
        ];
    }
}
