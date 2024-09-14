<?php

namespace Domain\Project\Actions;

use Domain\Project\Models\Project;
use Lorisleiva\Actions\Concerns\AsAction;
use Domain\Translation\Actions\StoreTranslations;
use Domain\Project\Actions\Cache\CleareActiveProjectCache;

class UpdateProject
{
    use AsAction;

    /**
     * Create the Project record.
     *
     * @param array $data  The data to create the Project record.
     */
    public function handle(Project $project, array $data): Project
    {
        $dataCollection = collect($data);

        // Validation already handled in the FormRequest.
        $project->update(
            $dataCollection->only((new Project())->getFillable())->toArray()
        );

        // Validation already handled in the FormRequest.
        StoreTranslations::run(
            $project,
            $dataCollection->only(config('localized-routes.supported_locales'))->toArray()
        );

        // Clear the cache for the project list.
        CleareActiveProjectCache::run();

        return $project->loadTranslations([
            'project_id',
            'locale',
            'title',
            'slug',
            'description',
        ]);
    }
}
