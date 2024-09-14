<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use Domain\Blog\Models\Post;
use Domain\Blog\Actions\SavePost;
use Illuminate\Database\Eloquent\Model;
use App\Filament\Resources\Blog\PostResource;
use App\Filament\Resources\Blog\Traits\SetPostFeaturedImage;
use App\Filament\Resources\Abstract\Pages\AbstractEditWithTranslationRecord;

class EditPostPage extends AbstractEditWithTranslationRecord
{
    use SetPostFeaturedImage;

    protected static string $resource = PostResource::class;

    /**
     * Update the record.
     */
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Post $record */
        $record = SavePost::run($data, $record);

        $this->data['featured_image_id'] = $this->setPostFeaturedImage($record);

        return $record;
    }

    /**
     * Mutate the form data before filling the form.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = parent::mutateFormDataBeforeFill($data);

        /** @var Post $record */
        $record = $this->record;

        $data['tags'] = $record->tags->pluck('id')->toArray();
        $data['category_id'] = $record->categories->pluck('id')->first();

        return $data;
    }

    /**
     * Refresh the listed form data after saving the record.
     */
    protected function afterSave(): void
    {
        $locales = config('localized-routes.supported_locales');
        $data = ['author_id', 'published_at'];

        // Populate the meta data for the record.
        $this->record->load('translations.metas');
        $metas = $this->loadMetaData($this->record, $this->data);
        foreach ($locales as $locale) {
            $this->data[$locale]['meta'] = $metas[$locale]['meta'];
        }

        $this->refreshFormData($data);
    }
}
