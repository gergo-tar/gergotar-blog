<?php

namespace Domain\Blog\Actions;

use Carbon\Carbon;
use Domain\Blog\Models\Post;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\SetTranslationPageMeta;

class SetPostMeta
{
    use AsAction;

    /**
     * Set the meta tags for the post.
     *
     * @param  Post  $post  The post model.
     */
    public function handle(Post $post): void
    {
        $img = $this->getPostImg($post);
        $url = $post->translation->url;
        $tags = $post->tags_names;
        $category = $post->category->translation->name;

        SEOMeta::setCanonical($url);

        // Open Graph
        $this->setPageOpenGraph($post, $url, $img, $category, $tags);

        // JSON-LD
        $this->setPageJsonLd($post, $url, $img, array_merge([$category], $tags));

        // Set the translation meta.
        SetTranslationPageMeta::run($post->translation);
    }

    /**
     * Get the website logo.
     */
    private function getLogo(): array
    {
        $logo = config('img.logo');
        $logo['url'] = asset($logo['url']);

        return $logo;
    }

    /**
     * Get the post image.
     *
     * In case the post doesn't have a featured image, the default image is used.
     *
     * @param  Post  $post  The post model
     */
    private function getPostImg(Post $post): array
    {
        if ($post->featuredImage) {
            return [
                'url' => $post->featuredImage->url,
                'height' => $post->featuredImage->height,
                'width' => $post->featuredImage->width,
            ];
        }

        $img = config('img.profile');
        $img['url'] = asset($img['url']);

        return $img;
    }

    /**
     * Set Page Open Graph properties.
     *
     * @param  Post  $post  The post model
     * @param string $url  Page URL
     * @param  array  $img  Page image
     * @param  string  $category  Page category's name
     * @param  array  $tags  Page tags list
     */
    private function setPageOpenGraph(Post $post, string $url, array $img, string $category, array $tags): void
    {
        $articleAttributes = [
            'published_time' => $post->published_at,
            'modified_time' => $post->updated_at,
            'expiration_time' => Carbon::now()->addWeek(),
            'author' => $post->author->name,
            'section' => $category,
        ];

        if (count($tags)) {
            $articleAttributes['tag'] = $tags;
        }

        OpenGraph::setUrl($url)
            ->addImages([$img['url']])
            ->setType('article')
            ->setArticle($articleAttributes);
    }

    /**
     * Set Page JSON-LD.
     *
     * @param  Post  $post  The post model
     * @param  string  $url  Page URL
     * @param  array  $img  Page image
     * @param  array  $keywords  Page keywords
     */
    private function setPageJsonLd(Post $post, string $url, array $img, array $keywords): void
    {
        $logo = $this->getLogo();

        JsonLd::setType('Article');
        JsonLd::setUrl($url);
        JsonLd::addValue('headline', $post->translation->title);
        JsonLd::addImage([
            '@type' => 'ImageObject',
            'url' => $img['url'],
            'height' => $img['height'],
            'width' => $img['width'],
        ]);
        JsonLd::addValue('author', [
            '@type' => 'Person',
            'name' => $post->author->name,
            'url' => route('home'),
        ]);
        JsonLd::addValue('inLanguage', app()->getLocale());
        JsonLd::addValue('publisher', [
            '@type' => 'Organization',
            'name' => get_blog_owner_name(app()->getLocale()),
            'url' => route('home'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $logo['url'],
                'width' => $logo['width'],
                'height' => $logo['height'],
            ],
        ]);
        JsonLd::addValue('articleSection', $post->category->translation->name);
        JsonLd::addValue('keywords', implode(', ', $keywords));
        // JsonLd::addValue('wordCount', str_word_count(strip_tags($post->translation->content)));
        JsonLd::addValue('datePublished', $post->published_at?->format('Y-m-d H:i:s'));
        JsonLd::addValue('dateModified', $post->updated_at?->format('Y-m-d H:i:s'));
    }
}
