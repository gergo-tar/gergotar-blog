<?php

namespace Domain\Meta\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Lorisleiva\Actions\Concerns\AsAction;

class StoreMetas
{
    use AsAction;

    /**
     * Store Model's metas.
     *
     * @param  Model  $model  The model.
     * @param  array  $data  The data to store in metas.
     */
    public function handle(Model $model, array $data): Collection
    {
        if (! method_exists($model, 'metas')) {
            return collect();
        }

        // Normalize data.
        $data = Arr::map($data, function (mixed $meta, string $key) use ($model) {
            if (is_string($meta)) {
                return [
                    'key' => $key,
                    'type' => $this->getMetaType($meta),
                    'value' => $meta,
                    'metable_type' => $model::class,
                ];
            }

            if (! isset($meta['key'])) {
                $meta['key'] = $key;
            }

            if (! isset($meta['value'])) {
                $meta['value'] = $meta;
            }

            $meta['type'] = $this->getMetaType($meta['value']);
            $meta['metable_type'] = $model::class;

            return $meta;
        });

        // Delete removed metas.
        $model->metas()->whereNotHasKey(Arr::pluck($data, 'key'))->delete();

        // Upsert metas.
        return collect($data)->map(function (array $meta) use ($model) {
            return $model->metas()->updateOrCreate(
                ['key' => $meta['key']],
                $meta
            );
        });
    }

    /**
     * Get the meta type.
     *
     * @param  mixed  $value  The value.
     */
    private function getMetaType(mixed $value): string
    {
        if (is_string($value)) {
            return 'string';
        }

        if (is_int($value)) {
            return 'integer';
        }

        if (is_float($value)) {
            return 'float';
        }

        if (is_bool($value)) {
            return 'boolean';
        }

        if (is_array($value)) {
            return 'array';
        }

        if (is_object($value)) {
            return 'object';
        }

        return 'string';
    }
}
