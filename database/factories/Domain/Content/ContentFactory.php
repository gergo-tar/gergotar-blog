<?php

namespace Database\Factories\Domain\Content;

use Illuminate\Support\Str;
use Domain\Content\Models\Content;
use Domain\Content\Models\ContentTranslation;
use Database\Factories\Domain\Translation\AbstractModelHasTranslationFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Content\Models\Content>
 */
class ContentFactory extends AbstractModelHasTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Content::class;

    /**
     * The name of the factory's corresponding translation model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $translationModel = ContentTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(1);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
        ];
    }

    /**
     * Indicate that the category has all supported translations.
     *
     * @param  int $count  The number of metas to create.
     * @param  array<string, mixed>  $attributes  The metas attributes.
     *
     * @return $this
     */
    public function hasSupportedTranslationsWithMetas(int $count = 1, array $attributes = []): self
    {
        $self = $this;
        $locales = config('localized-routes.supported_locales');
        foreach ($locales as $locale) {
            $self = $self->has(
                $this->translationModel::factory()->state([
                    'locale' => $locale,
                ])->hasMetas($count, $attributes),
                'translations'
            );
        }

        return $self;
    }

    /**
     * Indicate that it is an about content.
     */
    public function about(): static
    {
        return $this->state(fn () => [
            'title' => 'About Me',
            'slug' => 'about-me',
        ]);
    }

    /**
     * Indicate that it is a blog content.
     */
    public function blog(): static
    {
        return $this->state(fn () => [
            'title' => 'Blog',
            'slug' => 'blog',
        ]);
    }

    /**
     * Indicate that it is a contact content.
     */
    public function contact(): static
    {
        return $this->state(fn () => [
            'title' => 'Contact',
            'slug' => 'contact',
        ]);
    }

    /**
     * Indicate that it is an intro content.
     */
    public function intro(): static
    {
        return $this->state(fn () => [
            'title' => 'Intro',
            'slug' => 'intro',
        ]);
    }
}
