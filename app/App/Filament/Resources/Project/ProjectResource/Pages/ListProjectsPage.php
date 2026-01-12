<?php

namespace App\Filament\Resources\Project\ProjectResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Project\ProjectResource;
use Filament\Resources\Pages\ListRecords;

class ListProjectsPage extends ListRecords
{
    protected static string $resource = ProjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
