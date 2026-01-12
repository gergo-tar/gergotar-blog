<?php

namespace App\Filament\Resources\Category\CategoryResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Category\CategoryResource;
use Filament\Resources\Pages\ListRecords;

class ListCategoriesPage extends ListRecords
{
    protected static string $resource = CategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
