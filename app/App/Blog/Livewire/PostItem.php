<?php

namespace App\Blog\Livewire;

use Livewire\Component;

class PostItem extends Component
{
    public $post;

    public function render()
    {
        return view('Blog::livewire.post-item');
    }
}
