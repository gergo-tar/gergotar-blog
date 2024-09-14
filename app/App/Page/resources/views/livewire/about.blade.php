<div class="container mx-auto">
    <div class="py-16 lg:py-20">
        <div>
            <img src="{{ asset('img/icon-uses.png') }}" alt="icon uses" />
        </div>
        <h1 class="pt-5 text-4xl font-semibold font-body text-primary dark:text-white md:text-5xl lg:text-6xl">
            {{ __('Page::about.index') }}
        </h1>

        <div class="pt-16 lg:pt-20">
            <div class="flex flex-col items-center justify-center lg:flex-row">
                <div class="w-full lg:w-1/3">
                    <img src="{{ asset('img/gergo-profile.jpg') }}" alt="uses" class="rounded-lg max-h-96" />
                </div>

                <div class="w-full pt-4 text-lg font-light lg:w-2/3 lg:p-5 font-body text-primary dark:text-white">
                    {!! $content ? $content->translation->content : __('Page::about.content') !!}
                </div>
            </div>
        </div>

        {{-- Technologies --}}
        <div class="pt-16 lg:pt-20">
            <div class="flex items-center pb-6">
                <img src="{{ asset('img/icon-code.png') }}" alt="icon code" class="w-8" />
                <h3 class="ml-3 text-2xl font-semibold font-body text-primary dark:text-white">
                    {{ __('Page::about.technologies.title') }}
                </h3>
            </div>
            <ul class="pl-10 list-disc">
                <li class="text-lg font-light font-body text-primary dark:text-white">
                    <span class="font-medium"><a href="laravel.com" target="_blank"
                            class="underline text-green hover:text-secondary dark:text-green-light dark:hover:text-secondary">Laravel</a>:</span>
                    {{ __('Page::about.technologies.list.laravel') }}
                </li>
                <li class="pt-5 text-lg font-light font-body text-primary dark:text-white">
                    <span class="font-medium"><a href="vuejs.org" target="_blank"
                            class="underline text-green hover:text-secondary dark:text-green-light dark:hover:text-secondary">Vue.js</a>:</span>
                    {{ __('Page::about.technologies.list.vue') }}
                </li>
                <li class="pt-5 text-lg font-light font-body text-primary dark:text-white">
                    <span class="font-medium"><a href="tailwindcss.com" target="_blank"
                            class="underline text-green hover:text-secondary dark:text-green-light dark:hover:text-secondary">Tailwind
                            CSS</a>:</span>
                    {{ __('Page::about.technologies.list.tailwind') }}
                </li>
                <li class="pt-5 text-lg font-light font-body text-primary dark:text-white">
                    <span class="font-medium"><a href="laravel-livewire.com" target="_blank"
                            class="underline text-green hover:text-secondary dark:text-green-light dark:hover:text-secondary">Livewire</a>:</span>
                    {{ __('Page::about.technologies.list.livewire') }}
                </li>
            </ul>
        </div>

        {{-- Projects --}}
        <div class="pt-16 lg:pt-20">
            <livewire:project.project-list />
        </div>
    </div>
</div>
