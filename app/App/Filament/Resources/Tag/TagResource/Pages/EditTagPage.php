<?php

namespace App\Filament\Resources\Tag\TagResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Domain\Tag\Actions\UpdateTag;
use App\Filament\Resources\Tag\TagResource;
use App\Filament\Resources\Abstract\Pages\AbstractEditWithTranslationRecord;

class EditTagPage extends AbstractEditWithTranslationRecord
{
    protected static string $resource = TagResource::class;

    /**
     * Update the record.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return UpdateTag::run($record, $data);
    }
}
