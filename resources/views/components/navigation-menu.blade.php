<nav class="container mx-auto">
    <div class="flex items-center justify-between py-6 lg:py-10">
        <a href="{{ route('home') }}" wire:navigate class="flex items-center">
            <span class="mr-2">
                <x-logo />
            </span>
            <p class="hidden text-2xl font-bold font-body text-primary dark:text-white lg:block">
                {{ get_blog_owner_name(app()->getLocale()) }}
            </p>
        </a>
        <div class="flex items-center lg:hidden">
            <i class="mr-8 text-3xl cursor-pointer bx text-primary dark:text-white" @click="themeSwitch()"
                :class="isDarkMode ? 'bxs-sun' : 'bxs-moon'"></i>

            <svg width="24" height="15" xmlns="http://www.w3.org/2000/svg" @click="isMobileMenuOpen = true"
                class="cursor-pointer fill-current text-primary dark:text-white dark:fill-white">
                <g fill-rule="evenodd">
                    <rect width="24" height="3" rx="1.5" />
                    <rect x="8" y="6" width="16" height="3" rx="1.5" />
                    <rect x="4" y="12" width="20" height="3" rx="1.5" />
                </g>
            </svg>
        </div>
        <div class="hidden lg:block">
            <ul class="flex items-center">

                <li class="relative mb-1 mr-6 group">
                    <div
                        class="absolute bottom-0 left-0 z-20 w-full h-0 transition-all opacity-75 group-hover:h-2 group-hover:bg-yellow">
                    </div>
                    <a href="{{ route('home') }}"
                        class="relative z-30 block px-2 text-lg font-medium transition-colors font-body text-primary group-hover:text-green dark:text-white dark:group-hover:text-secondary"
                        wire:navigate>
                            {{ __('Page::home.index') }}
                        </a>
                </li>

                <li class="relative mb-1 mr-6 group">
                    <div
                        class="absolute bottom-0 left-0 z-20 w-full h-0 transition-all opacity-75 group-hover:h-2 group-hover:bg-yellow">
                    </div>
                    <a href="{{ route('blog') }}"
                        class="relative z-30 block px-2 text-lg font-medium transition-colors font-body text-primary group-hover:text-green dark:text-white dark:group-hover:text-secondary"
                        wire:navigate>
                            {{ __('Blog::blog.index') }}
                        </a>
                </li>

                <li class="relative mb-1 mr-6 group">
                    <div
                        class="absolute bottom-0 left-0 z-20 w-full h-0 transition-all opacity-75 group-hover:h-2 group-hover:bg-yellow">
                    </div>
                    <a href="{{ route('about') }}"
                        class="relative z-30 block px-2 text-lg font-medium transition-colors font-body text-primary group-hover:text-green dark:text-white dark:group-hover:text-secondary"
                        wire:navigate>
                        {{ __('Page::about.index') }}
                    </a>
                </li>

                <li class="relative mb-1 mr-6 group">
                    <div
                        class="absolute bottom-0 left-0 z-20 w-full h-0 transition-all opacity-75 group-hover:h-2 group-hover:bg-yellow">
                    </div>
                    <a href="{{ route('contact') }}"
                        class="relative z-30 block px-2 text-lg font-medium transition-colors font-body text-primary group-hover:text-green dark:text-white dark:group-hover:text-secondary"
                        wire:navigate>
                        {{ __('Page::contact.index') }}
                    </a>
                </li>

                <li>
                    <i class="text-3xl cursor-pointer bx text-primary dark:text-white" @click="themeSwitch()"
                        :class="isDarkMode ? 'bxs-sun' : 'bxs-moon'"></i>
                </li>
            </ul>
        </div>
    </div>
    <div class="fixed inset-0 z-50 flex transition-opacity bg-black opacity-0 pointer-events-none bg-opacity-80 lg:hidden modal-wrapper"
    :class="isMobileMenuOpen ? 'opacity-100 pointer-events-auto' : ''">
    <div class="absolute w-full h-full backdrop backdrop-shaded scroll-unlock lg:hidden"
    x-show.transition.opacity.duration.600ms="isMobileMenuOpen" @click="isMobileMenuOpen = false"></div>
        <div class="z-10 w-2/3 p-4 ml-auto bg-green md:w-1/3">
            <i class="absolute top-0 right-0 mt-4 mr-4 text-4xl text-white cursor-pointer bx bx-x"
                @click="isMobileMenuOpen = false"></i>
            <ul class="flex flex-col mt-8">

                <li class="">
                    <a href="{{ route('home') }}" class="block px-2 mb-3 text-lg font-medium text-white font-body"
                        wire:navigate>
                        {{ __('Page::home.index') }}
                    </a>
                </li>

                <li class="">
                    <a href="{{ route('blog') }}" class="block px-2 mb-3 text-lg font-medium text-white font-body"
                        wire:navigate>
                            {{ __('Blog::blog.index') }}
                        </a>
                </li>

                <li class="">
                    <a href="{{ route('about') }}" class="block px-2 mb-3 text-lg font-medium text-white font-body"
                        wire:navigate>
                        {{ __('Page::about.index') }}
                    </a>
                </li>

                <li class="">
                    <a href="{{ route('contact') }}" class="block px-2 mb-3 text-lg font-medium text-white font-body"
                        wire:navigate>
                        {{ __('Page::contact.index') }}
                    </a>
                </li>

            </ul>
        </div>
    </div>
</nav>
