<?php

namespace App\Filament\Resources\Blog\PostResource\Forms;

use Filament\Actions\Action;
use Domain\Tag\Actions\CreateTag;
use Domain\Tag\Actions\GetTagList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Domain\User\Actions\GetEditorUserList;
use Domain\Category\Actions\CreateCategory;
use Domain\Category\Actions\GetCategoryList;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\Tag\TagResource\Forms\TagForm;
use App\Filament\Resources\Category\CategoryResource\Forms\CategoryForm;

class PostInfoForm
{
    public static function make(): array
    {
        $hasRecord = request()->route('record') !== null;

        return [
            Select::make('author_id')
                ->label(__('Blog::post.author'))
                ->options(GetEditorUserList::run())
                ->relationship('author', 'name')
                ->when($hasRecord, fn ($component) => $component->required())
                ->searchable()
                ->columns(2),
            DateTimePicker::make('published_at')
                ->label(__('Blog::post.published_at'))
                ->when($hasRecord, fn ($component) => $component->required())
                ->native(false)
                ->columns(2),
            Select::make('category_id')
                ->label(__('Category::category.singular'))
                ->options(GetCategoryList::run())
                ->searchable()
                ->required()
                ->preload()
                ->createOptionForm(CategoryForm::make())
                ->createOptionAction(function (Action $action) {
                    $action->action(function (array $data, CreateCategory $createCategory) {
                        $createCategory::run($data);
                        // refresh the category list
                        return GetCategoryList::run();
                    });
                }),
            Select::make('tags')
                ->label(__('Tag::tag.plural'))
                ->relationship('tags', 'id')
                ->multiple()
                ->options(GetTagList::run())
                ->searchable()
                ->preload()
                ->createOptionForm(TagForm::make())
                ->createOptionAction(function (Action $action) {
                    $action->action(fn (array $data, CreateTag $createTag) => $createTag::run($data));
                }),
            Toggle::make('is_published')
                ->label(__('Blog::post.is_published'))
                ->required(),

        ];
    }
}
