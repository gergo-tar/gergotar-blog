<?php

namespace Database\Factories\Domain\Content;

use Domain\Meta\Models\Meta;
use Domain\Content\Models\ContentTranslation;
use Database\Factories\Domain\Translation\AbstractTranslationFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Content\Models\ContentTranslation>
 */
class ContentTranslationFactory extends AbstractTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = ContentTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $languages = config('localized-routes.supported_locales');

        return [
            'locale' => $languages[array_rand($languages)],
            'content' => $this->faker->paragraph(1),
        ];
    }

    /**
     * Indicate that the translation has Metas.
     *
     * @return $this
     */
    public function hasMetas(int $count = 1, array $attributes = []): self
    {
        return $this->has(
            Meta::factory($count)->state(array_merge($attributes, [
                'metable_id' => $this->model::factory(),
                'metable_type' => ContentTranslation::class,
            ])),
            'metas'
        );
    }
}
