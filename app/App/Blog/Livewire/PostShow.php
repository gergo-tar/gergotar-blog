<?php

namespace App\Blog\Livewire;

use Livewire\Component;
use Domain\Blog\Actions\SetPostMeta;
use Domain\Blog\Actions\Cache\GetCachedPublishedPost;

class PostShow extends Component
{
    public $post;

    public function mount(string $slug)
    {
        $this->post = GetCachedPublishedPost::run($slug);

        if (! $this->post) {
            abort(404, __('http-statuses.notFound', ['name' => __('Blog::post.singular')]));
        }

        $postLocale = $this->post->translations->where('slug', $slug)->first()->locale;
        if ($postLocale !== app()->getLocale()) {
            // Redirect to the correct locale.
            return redirect()->route("{$postLocale}.blog.posts.show", [
                'slug' => $this->post->translations->where('locale', $postLocale)->first()->slug,
            ]);
        }

        // Set the post meta.
        SetPostMeta::run($this->post);
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('Blog::livewire.post-detail')
            ->title($this->post->translation->title);
    }
}
