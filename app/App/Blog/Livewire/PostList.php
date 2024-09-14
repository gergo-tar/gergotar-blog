<?php

namespace App\Blog\Livewire;

use Livewire\Component;
use Domain\Blog\Actions\Cache\GetCachedPublishedPostList;

class PostList extends Component
{
    public $limit;

    public $isPaginated = false;

    public function mount(int $limit = 6)
    {
        $this->limit = $limit;
    }

    public function render()
    {
        return view('Blog::livewire.post-list', [
            'posts' => GetCachedPublishedPostList::run(
                isPaginated: $this->isPaginated,
                limit: $this->limit
            ),
        ]);
    }
}
