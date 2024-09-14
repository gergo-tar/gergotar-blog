<?php

namespace App\Page\Livewire;

use Livewire\Component;
use Domain\Content\Actions\SetPageMeta;
use Domain\Content\Actions\GetContentBySlug;

class Contact extends Component
{
    public $content;
    public $url;

    public function mount()
    {
        $this->content = GetContentBySlug::run('contact');
        $this->url = route('contact');

        // SEO tags.
        SetPageMeta::run($this->content, $this->url);
    }

    public function render()
    {
        return view('Page::livewire.contact')->title(__('Page::contact.index'));
    }
}
