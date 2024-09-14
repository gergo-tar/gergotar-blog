<?php

namespace App\Page\Livewire;

use Livewire\Component;
use Domain\Content\Actions\SetPageMeta;
use Domain\Blog\Actions\CountPublishedPosts;
use Domain\Content\Actions\GetContentBySlug;

class Home extends Component
{
    public $postCount;
    public $content;
    public $url;

    public function mount()
    {
        $this->postCount = CountPublishedPosts::run();
        $this->content = GetContentBySlug::run('intro');
        $this->url = route('home');

        // SEO tags.
        $this->setSeoTags();
    }

    public function render()
    {
        return view('Page::livewire.home');
    }

    /**
     * Set the SEO tags for the page.
     */
    private function setSeoTags()
    {
        // Add social media links to the JSON-LD
        $sameAs = [];
        if (config('social.github')) {
            $sameAs[] = config('social.github');
        }
        if (config('social.linkedin')) {
            $sameAs[] = config('social.linkedin');
        }

        SetPageMeta::run($this->content, $this->url, compact('sameAs'));
    }
}
