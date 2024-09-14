<?php

namespace App\Filament\Resources\Tag\TagResource\Forms;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Domain\Tag\Models\TagTranslation;
use App\Filament\Resources\Abstract\Forms\AbstractWithTranslationForm;

class TagForm extends AbstractWithTranslationForm
{
    public static function make(): array
    {
        return [self::getTranslationTab()];
    }

    protected static function setTranslationTab(string $locale): array
    {
        $isDefaultLocale = $locale === config('app.fallback_locale');

        return [
            TextInput::make("{$locale}.name")
                ->label(__('translations.attributes.name', ['locale' => $locale]))
                ->when($isDefaultLocale, fn ($component) => $component->required())
                ->requiredWith("{$locale}.description")
                ->unique(
                    table: TagTranslation::class,
                    column: 'name',
                    modifyRuleUsing: fn ($rule, $record) => $rule
                        ->where('locale', $locale)
                        ->when($record, fn ($rule) => $rule->ignore($record->id, 'tag_id'))
                )
                ->maxLength(255),
            Textarea::make("{$locale}.description")
                ->label(__('translations.attributes.description', ['locale' => $locale])),
        ];
    }
}
