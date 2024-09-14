<?php

namespace Database\Factories\Domain\Tag;

use Domain\Tag\Models\Tag;
use Domain\Tag\Models\TagTranslation;
use Database\Factories\Domain\Translation\AbstractModelHasTranslationFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Tag\Models\Tag>
 */
class TagFactory extends AbstractModelHasTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Tag::class;

    /**
     * The name of the factory's corresponding translation model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $translationModel = TagTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [];
    }
}
