<?php

namespace Database\Factories\Domain\Blog;

use Illuminate\Support\Str;
use Domain\Meta\Models\Meta;
use Domain\User\Models\User;
use Domain\Blog\Models\PostTranslation;
use Database\Factories\Domain\Translation\AbstractTranslationFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Blog\Models\PostTranslation>
 */
class PostTranslationFactory extends AbstractTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = PostTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $author = User::factory();
        $title = $this->faker->unique()->sentence(1);
        $languages = config('localized-routes.supported_locales');

        return [
            'locale' => $languages[array_rand($languages)],
            'title' => $title,
            'slug' => Str::slug($title),
            'content' => $this->faker->paragraph,
            'excerpt' => $this->faker->sentence(2),
            'created_by' => $author,
            'updated_by' => $author,
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
                'metable_type' => PostTranslation::class,
            ])),
            'metas'
        );
    }
}
