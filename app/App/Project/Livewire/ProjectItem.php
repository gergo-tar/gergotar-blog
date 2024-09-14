<?php

namespace App\Project\Livewire;

use Livewire\Component;

class ProjectItem extends Component
{
    public $project;

    public function render()
    {
        return view('Project::livewire.project-item');
    }
}
