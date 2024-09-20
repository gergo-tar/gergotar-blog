<div class="py-16 lg:py-20">
    <div>
        <img src="{{ asset('img/icon-blog.png') }}" alt="icon newspaper" />
    </div>

    <h1 class="pt-5 text-4xl font-semibold font-body text-primary dark:text-white md:text-5xl lg:text-6xl">
        {{ __('Blog::blog.index') }}
    </h1>

    <div class="pt-3 sm:w-3/4">
        <div class="text-xl font-light font-body text-primary dark:text-white">
            {!! $content ? $content->translation->content : __('Newsletter::newsletter.subscribe.description') !!}
        </div>
    </div>

    {{-- Subscribe form --}}
    <div class="py-6 lg:py-10">
        <livewire:newsletter.subscribe-form />
    </div>

    <div class="pt-8 lg:pt-12">
        <livewire:blog.post-list-paginated :limit="5" />
    </div>
</div>

