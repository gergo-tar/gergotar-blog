<?php

namespace App\Filament\Resources\Tag\TagResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use Domain\Tag\Actions\CreateTag;
use App\Filament\Resources\Tag\TagResource;

class CreateTagPage extends CreateRecord
{
    protected static string $resource = TagResource::class;

    /**
     * Create the record.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return CreateTag::run($data);
    }
}
