<?php

namespace App\Filament\Resources\Content\ContentResource\Forms;

use Domain\Content\Models\Content;
use Filament\Forms\Components\Section;
use FilamentTiptapEditor\TiptapEditor;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\Enums\TiptapOutput;
use App\Filament\Resources\Abstract\Forms\AbstractWithTranslationForm;

class ContentForm extends AbstractWithTranslationForm
{
    public static function make(): array
    {
        return [
            TextInput::make('title')
                ->label(__('general.title'))
                ->required()
                ->unique(Content::class, 'title', ignoreRecord: true)
                ->maxLength(255),
            self::getTranslationTab()
        ];
    }

    protected static function setTranslationTab(string $locale): array
    {
        $isDefaultLocale = $locale === config('app.fallback_locale');

        return [
            TiptapEditor::make("{$locale}.content")
                ->label(__('translations.attributes.content', ['locale' => $locale]))
                ->profile('default')
                ->output(TiptapOutput::Html)
                ->maxContentWidth('5xl')
                ->when($isDefaultLocale, fn ($component) => $component->required())
                ->requiredWith("{$locale}.title"),
            Section::make('Meta')
                ->description(__('general.meta.info'))
                ->schema([
                    TextInput::make("{$locale}.meta.title")
                        ->label("Meta Title Tag ({$locale})")
                        ->requiredWith("{$locale}.meta.description")
                        ->maxLength(60),
                    Textarea::make("{$locale}.meta.description")
                        ->label("Meta Description Tag ({$locale})")
                        ->maxLength(160),
                ]),
        ];
    }
}
