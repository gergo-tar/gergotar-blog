<?php

namespace Database\Factories\Domain\Project;

use Database\Factories\Domain\Translation\AbstractTranslationFactory;
use Domain\Project\Models\ProjectTranslation;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Project\Models\ProjectTranslation>
 */
class ProjectTranslationFactory extends AbstractTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ProjectTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(1);
        $languages = config('localized-routes.supported_locales');

        return [
            'locale' => $languages[array_rand($languages)],
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(1),
        ];
    }
}
