<?php

namespace Database\Factories\Domain\Category;

use Domain\Category\Models\Category;
use Domain\Category\Models\CategoryTranslation;
use Database\Factories\Domain\Translation\AbstractModelHasTranslationFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Category\Models\Category>
 */
class CategoryFactory extends AbstractModelHasTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Category::class;

    /**
     * The name of the factory's corresponding translation model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $translationModel = CategoryTranslation::class;

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
