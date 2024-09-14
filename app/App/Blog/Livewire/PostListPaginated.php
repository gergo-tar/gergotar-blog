<?php

namespace App\Blog\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Domain\Blog\Actions\Cache\GetCachedPublishedPostList;

class PostListPaginated extends Component
{
    use WithPagination;

    public $limit;

    public $isPaginated = true;

    public function mount(int $limit = 6)
    {
        $this->limit = $limit;
    }

    public function render()
    {
        return view('Blog::livewire.post-list', [
            'posts' => GetCachedPublishedPostList::run(
                isPaginated: $this->isPaginated,
                limit: $this->limit,
                page: $this->getPage()
            ),
        ]);
    }
}
