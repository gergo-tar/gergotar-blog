<?php

namespace App\Filament\Resources\Content\ContentResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Domain\Content\Actions\CreateContent;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Content\ContentResource;

class CreateContentPage extends CreateRecord
{
    protected static string $resource = ContentResource::class;

    /**
     * Create the record.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return CreateContent::run($data);
    }
}
