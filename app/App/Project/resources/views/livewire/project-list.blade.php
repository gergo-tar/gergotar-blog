<div>
    <div class="flex items-center pb-6">
        <img src="{{ asset('img/icon-project.png') }}" alt="icon project" />
        <h3 class="ml-3 text-2xl font-semibold font-body text-primary dark:text-white">
            {{ __('Page::home.projects.title') }}
        </h3>
    </div>

    @foreach ($projects as $project)
        <livewire:project.project-item :project="$project" :key="$project->id" />
    @endforeach
</div>
