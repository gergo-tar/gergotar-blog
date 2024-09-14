<?php

namespace App\Filament\Resources\Content\ContentResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Domain\Content\Actions\UpdateContent;
use App\Filament\Resources\Content\ContentResource;
use App\Filament\Resources\Abstract\Pages\AbstractEditWithTranslationRecord;

class EditContentPage extends AbstractEditWithTranslationRecord
{
    protected static string $resource = ContentResource::class;

    /**
     * Update the record.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return UpdateContent::run($record, $data);
    }
}
