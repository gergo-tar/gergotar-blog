<?php

namespace Database\Factories\Domain\Blog;

use Domain\Tag\Models\Tag;
use Domain\Blog\Models\Post;
use Domain\User\Models\User;
use Awcodes\Curator\Models\Media;
use Domain\Category\Models\Category;
use Domain\Blog\Models\PostTranslation;
use Database\Factories\Domain\Translation\AbstractModelHasTranslationFactory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Domain\Blog\Models\Post>
 */
class PostFactory extends AbstractModelHasTranslationFactory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Post::class;

    /**
     * The name of the factory's corresponding translation model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $translationModel = PostTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $author = User::factory();

        return [
            'author_id' => $author,
            'created_by' => $author,
            'updated_by' => $author,
        ];
    }

    /**
     * Indicate that the post has a category with default translation.
     *
     * @param  array<string, mixed>  $attributes
     * @return $this
     */
    public function hasCategoryDefaultTranslation(array $attributes = []): self
    {
        return $this->has(Category::factory()->hasDefaultTranslation($attributes), 'categories');
    }

    /**
     * Indicate that the post has a category with default translation.
     *
     * @return $this
     */
    public function hasCategoryWithSupportedTranslations(): self
    {
        return $this->has(Category::factory()->hasSupportedTranslations(), 'categories');
    }

    /**
     * Indicate that the post has a Featured Image.
     *
     * @param  array<string, mixed>  $attributes  The media attributes.
     *
     * @return $this
     */
    public function hasFeaturedImage(array $attributes = []): self
    {
        $media = Media::factory()->create($attributes);
        return $this->state(['featured_image_id' => $media->id]);
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
     * Indicate that the post has a tag with default translation.
     *
     * @param  array<string, mixed>  $attributes  The tag attributes.
     * @return $this
     */
    public function hasTagDefaultTranslation(array $attributes = []): self
    {
        return $this->has(Tag::factory()->hasDefaultTranslation($attributes), 'tags');
    }

    /**
     * Indicate that the post is published.
     *
     * @return $this
     */
    public function published(): self
    {
        return $this->state([
            'is_published' => true,
            'published_at' => now(),
        ]);
    }

    /**
     * Indicate that the post is not published.
     *
     * @return $this
     */
    public function unpublished(): self
    {
        return $this->state([
            'is_published' => false,
            'published_at' => null,
        ]);
    }
}
