<?php

namespace Database\Factories\Domain\Translation;

use Illuminate\Database\Eloquent\Factories\Factory;

abstract class AbstractModelHasTranslationFactory extends Factory
{
    /**
     * The name of the Translation model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $translationModel;

    /**
     * Indicate that the category has a default translation.
     *
     * @param  array<string, mixed>  $attributes
     * @return $this
     */
    public function hasDefaultTranslation(array $attributes = []): self
    {
        return $this->has($this->translationModel::factory()->default()->state($attributes), 'translations');
    }

    /**
     * Indicate that the category has all supported translations.
     *
     * @return $this
     */
    public function hasSupportedTranslations(): self
    {
        return $this->hasTranslations(count: count(config('localized-routes.supported_locales')));
    }

    /**
     * Indicate that the category has translations.
     *
     * @param  int  $count
     * @return $this
     */
    public function hasTranslations(int $count = 1): self
    {
        $self = $this;
        $locales = config('localized-routes.supported_locales');
        for ($i = 0; $i < $count; $i++) {
            $self = $self->has($this->translationModel::factory()->state([
                'locale' => $locales[$i],
            ]), 'translations');
        }

        return $self;
    }
}
