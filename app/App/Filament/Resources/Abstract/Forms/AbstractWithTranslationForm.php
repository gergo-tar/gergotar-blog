<?php

namespace App\Filament\Resources\Abstract\Forms;

use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;

abstract class AbstractWithTranslationForm
{
    abstract protected static function setTranslationTab(string $locale): array;

    /**
     * Get the translation tab.
     */
    protected static function getTranslationTab(?string $title = null): Tabs
    {
        return Tabs::make($title ?? __('translations.label'))
            ->tabs(self::getTranslationTabs())
            ->activeTab(self::getActiveTranslationTab())
            ->columnSpan(2);
    }

    /**
     * Get the active translation tab index.
     */
    protected static function getActiveTranslationTab(): int
    {
        return array_search(config('app.fallback_locale'), config('localized-routes.supported_locales')) + 1;
    }

    /**
     * Get the translation tabs with form fields.
     */
    protected static function getTranslationTabs(): array
    {
        $tabs = [];
        // Create tabs from available locales.
        foreach (config('localized-routes.supported_locales') as $locale) {
            $tabs[] = Tab::make($locale)
                ->schema(static::setTranslationTab($locale));
        }

        return $tabs;
    }
}
