<?php

namespace App\Filament\Resources\Abstract\Pages;

use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

abstract class AbstractEditWithTranslationRecord extends EditRecord
{
    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    /**
     * Mutate the form data before filling the form.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        return $this->loadMetaData($this->record, $data);
    }

    /**
     * Load the meta data for the record.
     *
     * @param  Model  $record  The record.
     * @param  array  $data  The data to load.
     */
    public function loadMetaData(Model $record, array $data): array
    {
        // Check if the record has translations
        if (! method_exists($record, 'translations')) {
            return $data;
        }

        // Load translations
        // @phpstan-ignore-next-line
        $translations = $record->translations;

        $hasMetas = $translations->isNotEmpty() && method_exists($translations->first(), 'metas');
        if ($hasMetas) {
            $translations->load('metas');
        }

        // Fill the tab fields with the translations
        foreach (config('localized-routes.supported_locales') as $language) {
            $translation = $translations->where('locale', $language)->first();
            if (! isset($data[$language])) {
                $data[$language] = $translation ?? [];
            }
            if ($hasMetas && $translation) {
                $data[$language]['meta'] = $translation->metas->pluck('value', 'key')->toArray();
            }
        }

        return $data;
    }
}
