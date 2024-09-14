<?php

namespace App\Filament\Resources\Project\ProjectResource\Forms;

use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Domain\Project\Models\ProjectTranslation;
use App\Filament\Resources\Abstract\Forms\AbstractWithTranslationForm;

class ProjectForm extends AbstractWithTranslationForm
{
    public static function make(): array
    {
        return [
            Section::make()
                ->columns(1)
                ->schema([
                    Toggle::make('is_active')
                        ->label(__('general.is_active'))
                        ->required(),
                    TextInput::make('url')
                        ->label(__('general.url'))
                        ->required()
                        ->url()
                        ->maxLength(100)
                ]),
            self::getTranslationTab(),
        ];
    }

    protected static function setTranslationTab(string $locale): array
    {
        $isDefaultLocale = $locale === config('app.fallback_locale');

        return [
            TextInput::make("{$locale}.title")
                ->label(__('translations.attributes.title', ['locale' => $locale]))
                ->when($isDefaultLocale, fn ($component) => $component->required())
                ->requiredWith("{$locale}.description")
                ->unique(
                    table: ProjectTranslation::class,
                    column: 'title',
                    modifyRuleUsing: fn ($rule, $record) => $rule
                        ->where('locale', $locale)
                        ->when($record, fn ($rule) => $rule->ignore($record->id, 'project_id'))
                )
                ->maxLength(255),
            Textarea::make("{$locale}.description")
                ->label(__('translations.attributes.description', ['locale' => $locale])),
        ];
    }
}
