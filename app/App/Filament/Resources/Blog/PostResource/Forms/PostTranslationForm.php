<?php

namespace App\Filament\Resources\Blog\PostResource\Forms;

use Filament\Forms\Components\Section;
use FilamentTiptapEditor\TiptapEditor;
use Domain\Blog\Models\PostTranslation;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use FilamentTiptapEditor\Enums\TiptapOutput;

class PostTranslationForm
{
    public static function make(string $locale): array
    {
        $isDefaultLocale = $locale === config('app.fallback_locale');

        return [
            TextInput::make("{$locale}.title")
                ->label(__('translations.attributes.title', ['locale' => $locale]))
                ->when($isDefaultLocale, fn ($component) => $component->required())
                ->requiredWith("{$locale}.content")
                ->unique(
                    table: PostTranslation::class,
                    column: 'title',
                    modifyRuleUsing: fn ($rule, $record) => $rule
                        ->where('locale', $locale)
                        ->when($record, fn ($rule) => $rule->ignore($record->id, 'post_id'))
                )
                ->maxLength(60),
            TiptapEditor::make("{$locale}.content")
                ->label(__('translations.attributes.content', ['locale' => $locale]))
                ->profile('default')
                // ->tools([]) // individual tools to use in the editor, overwrites profile
                // ->disk('string') // optional, defaults to config setting
                // ->directory('string or Closure returning a string') // optional, defaults to config setting
                // ->acceptedFileTypes(['array of file types']) // optional, defaults to config setting
                // ->maxFileSize('integer in KB') // optional, defaults to config setting
                ->output(TiptapOutput::Html) // optional, change the format for saved data, default is html
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
