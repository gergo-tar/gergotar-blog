<?php

namespace App\Filament\Resources\Project\ProjectResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use Domain\Project\Actions\CreateProject;
use App\Filament\Resources\Project\ProjectResource;

class CreateProjectPage extends CreateRecord
{
    protected static string $resource = ProjectResource::class;

    /**
     * Create the record.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return CreateProject::run($data);
    }
}
