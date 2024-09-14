<?php

namespace App\Filament\Resources\Blog\PostResource\Pages;

use Domain\Blog\Actions\SavePost;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\Blog\PostResource;
use App\Filament\Resources\Blog\Traits\SetPostFeaturedImage;

class CreatePostPage extends CreateRecord
{
    use SetPostFeaturedImage;

    protected static string $resource = PostResource::class;

    /**
     * Create the record.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $record = SavePost::run($data);

        $this->data['featured_image_id'] = $this->setPostFeaturedImage($record);

        return $record;
    }
}
