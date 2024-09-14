<footer class="container mx-auto">
    <div class="flex flex-col items-center justify-between py-10 border-t border-grey-lighter sm:flex-row sm:py-12">
        <div class="flex flex-col items-center mr-auto sm:flex-row">
            <a href="{{ route('home') }}" class="mr-auto sm:mr-6">
                <x-logo />
            </a>
            <p class="pt-5 font-light font-body text-primary dark:text-white sm:pt-0">
                ©{{ date('Y') }} {{ get_blog_owner_name(app()->getLocale()) }}.
            </p>
        </div>
        <div class="flex items-center pt-5 mr-auto sm:mr-0 sm:pt-0">
            @if(config('social.github'))
            <a href="{{ config('social.github') }}" target="_blank">
                <i
                    class="pl-5 text-4xl transition-colors text-primary dark:text-white hover:text-secondary dark:hover:text-secondary bx bxl-github"></i>
            </a>
            @endif
            @if(config('social.codepen'))
            <a href="{{ config('social.codepen') }}" target="_blank">
                <i
                    class="pl-5 text-4xl transition-colors text-primary dark:text-white hover:text-secondary dark:hover:text-secondary bx bxl-codepen"></i>
            </a>
            @endif
            @if(config('social.linkedin'))
            <a href="{{ config('social.linkedin') }}" target="_blank">
                <i
                    class="pl-5 text-4xl transition-colors text-primary dark:text-white hover:text-secondary dark:hover:text-secondary bx bxl-linkedin"></i>
            </a>
            @endif
        </div>
    </div>
</footer>
