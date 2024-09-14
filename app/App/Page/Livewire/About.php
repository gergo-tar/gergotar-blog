<?php

namespace App\Page\Livewire;

use Livewire\Component;
use Domain\Content\Actions\SetPageMeta;
use Domain\Content\Actions\GetContentBySlug;

class About extends Component
{
    public $content;
    public $url;

    public function mount()
    {
        $this->content = GetContentBySlug::run('about-me');
        $this->url = route('about');

        // SEO tags.
        SetPageMeta::run($this->content, $this->url);
    }

    public function render()
    {
        return view('Page::livewire.about')->title(__('Page::about.index'));
    }
}
