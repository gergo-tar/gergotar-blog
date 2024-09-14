<?php

namespace App\Filament\Resources\Category\CategoryResource\Pages;

use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use Domain\Category\Actions\CreateCategory;
use App\Filament\Resources\Category\CategoryResource;

class CreateCategoryPage extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    /**
     * Create the record.
     */
    protected function handleRecordCreation(array $data): Model
    {
        return CreateCategory::run($data);
    }
}
