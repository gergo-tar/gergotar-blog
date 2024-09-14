<?php

namespace Domain\Translation\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasTranslations
{
    use HasTranslationEagerLoadQuery;

    /**
     * Get the translations for the model.
     */
    public function translations(): HasMany
    {
        return $this->hasMany(self::getTranslationClass());
    }

    /**
     * Get the translation for the model.
     */
    public function translation(?string $locale = null): HasOne
    {
        $locale = $locale ?: app()->getLocale();

        return $this->hasOne(self::getTranslationClass())
            ->ofMany(
                [],
                fn (Builder $query) => $query
                    ->where(function ($subQuery) use ($locale) {
                        $subQuery->where('locale', $locale)
                            ->orWhere(function ($q) use ($locale) {
                                $q->where('locale', '!=', $locale)
                                    ->where('locale', config('app.fallback_locale'));
                            });
                    })
            );
    }

    /**
     * Eager load the locale translation.
     *
     * @param  array  $columns  The columns to load.
     */
    public function loadTranslation(array $columns = ['*']): self
    {
        return $this->load($this->getSingleTranslationEagerLoadQuery($columns));
    }

    /**
     * Eagar load the translations.
     *
     * @param  array  $columns  The columns to load.
     */
    public function loadTranslations(array $columns = ['*']): self
    {
        return $this->load($this->getMultipleTranslationEagerLoadQuery($columns));
    }

    /**
     * Get the translation class.
     */
    protected static function getTranslationClass(): string
    {
        return self::class . 'Translation';
    }
}
