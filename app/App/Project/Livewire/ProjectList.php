<?php

namespace App\Project\Livewire;

use Livewire\Component;
use Domain\Project\Actions\GetActiveProjects;

class ProjectList extends Component
{
    public function render()
    {
        return view('Project::livewire.project-list', [
            'projects' => GetActiveProjects::run(),
        ]);
    }
}
