<?php

namespace Database\Factories\Domain\Translation;

use Illuminate\Database\Eloquent\Factories\Factory;

abstract class AbstractTranslationFactory extends Factory
{
    /**
     * Indicate that the Translation is with the default locale.
     *
     * @return $this
     */
    public function default(): self
    {
        return $this->state([
            'locale' => config('app.locale'),
        ]);
    }
}
