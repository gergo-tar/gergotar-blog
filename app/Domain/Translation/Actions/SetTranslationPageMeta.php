<?php

namespace Domain\Translation\Actions;

use Artesaos\SEOTools\Facades\JsonLd;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Lorisleiva\Actions\Concerns\AsAction;
use Artesaos\SEOTools\Facades\TwitterCard;
use Domain\Translation\Models\AbstractTranslation;

class SetTranslationPageMeta
{
    use AsAction;

    /**
     * Set the translation meta tags for the page.
     *
     * @param  AbstractTranslation  $translation  The translation model with meta.
     */
    public function handle(AbstractTranslation $translation): void
    {
        if (!method_exists($translation, 'metas')) {
            return;
        }

        // Default Title.
        $title = get_blog_owner_name(app()->getLocale());

        if ($translation->metas && $translation->metas->count() > 0) {
            // In case the title is set as a Meta, we need to append it to the default title.
            $beforeTitle = $translation->metas->where('key', 'title')->first()?->value;
            if ($beforeTitle) {
                $title = $beforeTitle . config('seotools.meta.defaults.separator') . $title;
            }

            // Set the description.
            $description = $translation->metas->where('key', 'description')->first()?->value;
            if ($description) {
                SEOMeta::setDescription($description);
                OpenGraph::setDescription($description);
                JsonLd::setDescription($description);
            }
        }

        // Set the title.
        TwitterCard::setTitle($title);
        SEOMeta::setTitle($title);
        OpenGraph::setTitle($title);
        JsonLd::setTitle($title);
    }
}
