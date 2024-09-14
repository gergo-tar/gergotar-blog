<div>
    {{-- Hero section --}}
    <div class="py-16 border-b border-grey-lighter lg:py-20">
        <div>
            <img src="{{ asset('img/author.png') }}" class="w-16 h-16 rounded-full dark:bg-white dark:border-2 dark:border-secondary"
                alt="author" />
        </div>
        <h1 class="pt-3 text-4xl font-semibold font-body text-primary dark:text-white md:text-5xl lg:text-6xl">
            {{ __('Page::home.hero.title', ['name' => get_blog_owner_name(app()->getLocale())]) }}
        </h1>
        <p class="pt-3 text-xl font-light font-body text-primary dark:text-white">
            {{ __('Page::home.hero.subtitle') }}
        </p>
        <a href="{{ route('contact') }}" wire:navigate
            class="block px-10 py-4 mt-12 text-xl font-semibold text-center text-white transition-colors bg-secondary font-body hover:bg-green sm:inline-block sm:text-left sm:text-2xl">
            {{ __('Page::home.hero.cta') }}
        </a>
    </div>

    {{-- About the Blog section --}}
    @if($content)
    <div class="py-16 border-b border-grey-lighter lg:py-20">
        <div class="flex items-center pb-6">
            <img src="{{ asset('img/icon-story.png') }}" alt="icon story" />
            <h3 class="ml-3 text-2xl font-semibold font-body text-primary dark:text-white">
                {{ __('Blog::blog.about.title') }}
            </h3>
        </div>
        <div class="font-light font-body text-primary dark:text-white">
            {!! $content->translation->content !!}
        </div>
    </div>
    @endif

    {{-- Latest blog posts --}}
    @if ($postCount > 0)
        <div class="py-16 lg:py-20">
            <div class="flex items-center pb-6">
                <img src="{{ asset('img/icon-blog.png') }}" alt="icon newspaper" class="w-8" />
                <h3 class="ml-3 text-2xl font-semibold font-body text-primary dark:text-white">
                    {{ __('Blog::blog.posts.latest') }}
                </h3>
                <a href="{{ route('blog') }}"
                    class="flex items-center pl-10 italic transition-colors font-body text-green hover:text-secondary dark:text-green-light dark:hover:text-secondary"
                    wire:navigate>
                    {{ __('Blog::blog.posts.all') }}
                    <img src="{{ asset('img/long-arrow-right.png') }}" class="ml-3" alt="arrow right" />
                </a>
            </div>

            <livewire:blog.post-list :limit="3" />
        </div>
    @endif

    <div class="pb-16 lg:pb-20">
        <livewire:project.project-list />
    </div>
</div>
