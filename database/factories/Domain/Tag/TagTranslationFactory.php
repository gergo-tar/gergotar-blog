<?php

namespace Database\Factories\Domain\Tag;

use Database\Factories\Domain\Translation\AbstractTranslationFactory;
use Domain\Tag\Models\TagTranslation;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Tag\Models\TagTranslation>
 */
class TagTranslationFactory extends AbstractTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = TagTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(1);
        $languages = config('localized-routes.supported_locales');

        return [
            'locale' => $languages[array_rand($languages)],
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(1),
        ];
    }
}
