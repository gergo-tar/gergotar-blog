<?php

namespace App\Filament\Resources\Tag\TagResource\Pages;

use App\Filament\Resources\Tag\TagResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTagsPage extends ListRecords
{
    protected static string $resource = TagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
