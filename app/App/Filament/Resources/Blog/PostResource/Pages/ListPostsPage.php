<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Resources\Blog\PostResource;
use Filament\Resources\Pages\ListRecords;

class ListPostsPage extends ListRecords
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
