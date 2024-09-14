<?php

namespace Domain\Content\Actions;

use Domain\Content\Models\Content;
use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\SetTranslationPageMeta;

class SetPageMeta
{
    use AsAction;

    /**
     * Set the meta tags for the page.
     *
     * @param  Content|null  $content  The content model.
     * @param  string|null  $url  The page URL.
     * @param  array|null  $customLdAttributes  Custom JSON-LD attributes.
     */
    public function handle(
        ?Content $content = null,
        ?string $url = null,
        ?array $customLdAttributes = null
    ): void {
        $url = $url ?? route('home');

        SEOMeta::setCanonical($url);

        // Open Graph
        $this->setPageOpenGraph($url);

        // JSON-LD
        $this->setPageJsonLd($url, $customLdAttributes);

        // Set the translation meta.
        if ($content && $content->translation) {
            SetTranslationPageMeta::run($content->translation);
        }
    }

    /**
     * Set Page Open Graph properties.
     *
     * @param string $url  Page URL
     */
    private function setPageOpenGraph(string $url): void
    {
        OpenGraph::setUrl($url);
        OpenGraph::addProperty('type', 'website');
        OpenGraph::addImages([asset(config('img.profile.url'))]);
    }

    /**
     * Set Page JSON-LD.
     *
     * @param string $url  Page URL
     * @param array|null $customLdAttributes  Custom JSON-LD attributes
     */
    private function setPageJsonLd(string $url, ?array $customLdAttributes = null): void
    {
        JsonLd::setType(route('home') === $url ? 'WebSite' : 'WebPage');
        JsonLd::setUrl($url);
        JsonLd::addImage([
            '@type' => 'ImageObject',
            'url' => asset(config('img.profile.url')),
            'height' => 512,
            'width' => 640
        ]);
        JsonLd::addValue('author', [
            '@type' => 'Person',
            'name' => get_blog_owner_name(app()->getLocale()),
            'url' => route('home'),
        ]);
        JsonLd::addValue('inLanguage', app()->getLocale());

        if ($customLdAttributes) {
            foreach ($customLdAttributes as $key => $value) {
                JsonLd::addValue($key, $value);
            }
        }
    }
}
