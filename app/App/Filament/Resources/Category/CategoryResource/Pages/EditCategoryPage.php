<?php

namespace App\Filament\Resources\Category\CategoryResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Domain\Category\Actions\UpdateCategory;
use App\Filament\Resources\Category\CategoryResource;
use App\Filament\Resources\Abstract\Pages\AbstractEditWithTranslationRecord;

class EditCategoryPage extends AbstractEditWithTranslationRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Update the record.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return UpdateCategory::run($record, $data);
    }
}
