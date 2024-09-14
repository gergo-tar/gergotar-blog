<?php

namespace App\Filament\Resources\Project\ProjectResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Domain\Project\Actions\UpdateProject;
use App\Filament\Resources\Project\ProjectResource;
use App\Filament\Resources\Abstract\Pages\AbstractEditWithTranslationRecord;

class EditProjectPage extends AbstractEditWithTranslationRecord
{
    protected static string $resource = ProjectResource::class;

    /**
     * Update the record.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return UpdateProject::run($record, $data);
    }
}
