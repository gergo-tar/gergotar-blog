<a href="{{ $project->url }}" target="_blank"
    class="flex items-center justify-between px-4 py-4 mb-6 border border-grey-lighter sm:px-6">
    <span class="w-11/12 pr-8">
        <h4 class="text-lg font-semibold font-body text-primary dark:text-white">
            {{ $project->translation->title }}
        </h4>
        <p class="font-light font-body text-primary dark:text-white">
            {{ $project->translation->description }}
        </p>
    </span>
    <span class="w-1/12">
        <img src="{{ asset('img/chevron-right.png') }}" class="mx-auto" alt="chevron right" />
    </span>
</a>
