<?php

namespace App\Blog\Livewire;

use Livewire\Component;
use Domain\Content\Actions\SetPageMeta;
use Domain\Content\Actions\GetContentBySlug;

class Blog extends Component
{
    public $content;
    public $url;

    public function mount()
    {
        $this->content = GetContentBySlug::run('blog');
        $this->url = route('blog');

        SetPageMeta::run($this->content, $this->url);
    }

    public function render()
    {
        return view('Blog::livewire.blog')->title(__('Blog::blog.index'));
    }
}
