<?php

namespace Domain\Translation\Actions;

use Domain\Meta\Actions\StoreMetas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreTranslations
{
    use AsAction;

    /**
     * Store Model's translations.
     *
     * @param  Model  $model  The model.
     * @param  array  $data  The data to store in translations.
     * @param  array  $extra  The extra data to store in every translation.
     */
    public function handle(Model $model, array $data, array $extra = []): Collection
    {
        $translations = collect();
        if (! method_exists($model, 'translations')) {
            return $translations;
        }

        // @phpstan-ignore property.notFound
        $hasTranslations = $model->translations->isNotEmpty();

        foreach (config('localized-routes.supported_locales') as $locale) {
            if (! isset($data[$locale])) {
                continue;
            }

            // Remove empty values from array.
            $data[$locale] = array_filter_recursive($data[$locale]);

            if (empty($data[$locale])) {
                // If translation exists then delete it.
                $this->deleteTranslation($model, $locale, $hasTranslations);

                continue;
            }

            if (! $hasTranslations) {
                // If model doesn't have any translations then it is a new model so create its translations.
                $translations->push($this->createTranslation($model, $locale, $data, $extra));

                continue;
            }

            // Update model translations.
            $translations->push($this->updateTranslation($model, $locale, $data));
        }

        return $translations;
    }

    /**
     * Create Model translations.
     *
     * @param  Model  $model  The model.
     * @param  string  $locale  The language code.
     * @param  array  $data  The data containing all translations.
     * @param  array  $extra  The extra data to store in every translation.
     */
    private function createTranslation(Model $model, string $locale, array $data, array $extra = []): Model
    {
        $value = $data[$locale];
        $value['locale'] = $locale;

        // Get the meta data if translation has it.
        $meta = [];
        if (isset($value['meta'])) {
            $meta = $value['meta'];
            unset($value['meta']);
        }

        // If has extra data then merge it with the translation data.
        if (! empty($extra)) {
            $value = array_merge($value, $extra);
        }

        // @phpstan-ignore method.notFound
        $translation = $model->translations()->create($value);

        // In case the transaltion has meta data store them.
        $this->storeMetas($translation, $meta);

        return $translation;
    }

    /**
     * Delete Model translations.
     *
     * @param  Model  $model  The model.
     * @param  string  $locale  The language code.
     * @param  bool  $hasTranslations  The flag to check if Model has any translations.
     */
    private function deleteTranslation(Model $model, string $locale, bool $hasTranslations): void
    {
        if ($hasTranslations) {
            // If translations exists then delete it.
            // @phpstan-ignore method.notFound
            $model->translations()->where('locale', $locale)->delete();
        }
    }

    /**
     * Store Model's meta data.
     *
     * @param  Model  $model  The trnaslation model.
     * @param  array  $meta  The meta data to store.
     */
    private function storeMetas(Model $model, array $meta): void
    {
        // Add newly created metas to the meta data.
        // In case the Observer has created the metas.
        if (method_exists($model, 'metas')) {
            $model->load('metas');
            // Filter out newly created metas.
            // @phpstan-ignore property.notFound
            $newMetas = $model->metas->filter(function ($value) {
                return $value->created_at == $value->updated_at
                    && $value->created_at->gte(now()->subSeconds(5));
            });

            if ($newMetas->isNotEmpty()) {
                $meta = array_merge($meta, $newMetas->pluck('value', 'key')->toArray());
            }
        }

        // In case the transaltion has meta data store them.
        StoreMetas::run($model, $meta);
    }

    /**
     * Update Model translations.
     *
     * @param  Model  $model  The model.
     * @param  string  $locale  The language code.
     * @param  array  $data  The data containing all translations.
     * @param  array  $extra  The extra data to store in every translation.
     */
    private function updateTranslation(Model $model, string $locale, array $data, array $extra = []): Model
    {
        $value = $data[$locale];

        // Get the meta data if translation has it.
        $meta = [];
        if (isset($value['meta'])) {
            $meta = $value['meta'];
            unset($value['meta']);
        }

        // If has extra data then merge it with the translation data.
        if (! empty($extra)) {
            $value = array_merge($value, $extra);
        }
        // @phpstan-ignore method.notFound
        $translation = $model->translations()->where('locale', $locale)->updateOrCreate(
            ['locale' => $locale],
            $value
        );

        // In case the transaltion has meta data store them.
        $this->storeMetas($translation, $meta);

        return $translation;
    }
}
