<?php

namespace Database\Factories\Domain\Project;

use Domain\Project\Models\Project;
use Domain\Project\Models\ProjectTranslation;
use Database\Factories\Traits\FactoryHasActive;
use Database\Factories\Domain\Translation\AbstractModelHasTranslationFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Project\Models\Project>
 */
class ProjectFactory extends AbstractModelHasTranslationFactory
{
    use FactoryHasActive;

    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Project::class;

    /**
     * The name of the factory's corresponding translation model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $translationModel = ProjectTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => $this->faker->url,
        ];
    }
}
