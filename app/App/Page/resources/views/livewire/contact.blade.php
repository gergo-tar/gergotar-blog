<div class="container mx-auto">
    <div class="py-16 lg:py-20">
        <div>
            <img src="{{ asset('img/icon-contact.png') }}" alt="icon envelope" />
        </div>
        <h1 class="pt-5 text-4xl font-semibold font-body text-primary dark:text-white md:text-5xl lg:text-6xl">
            {{ __('Page::contact.index') }}
        </h1>
        <div class="pt-3 pr-2 mt-4 text-xl font-light sm:pt-0 font-body text-primary dark:text-white">
            {!! $content ? tiptap_converter()->asHTML($content->translation->content) : __('Page::contact.content') !!}
        </div>

        <livewire:form.contact-form />
    </div>
</div>
