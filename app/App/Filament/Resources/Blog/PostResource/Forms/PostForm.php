<?php

namespace App\Filament\Resources\Blog\PostResource\Forms;

use Filament\Forms\Components\Section;
use Awcodes\Curator\Components\Forms\CuratorPicker;
use App\Filament\Resources\Abstract\Forms\AbstractWithTranslationForm;

class PostForm extends AbstractWithTranslationForm
{
    public static function make(): array
    {
        return [
            Section::make()
                ->columns(2)
                ->schema([
                    Section::make()
                        ->schema(PostInfoForm::make())
                        ->columnSpan(1),
                    Section::make()
                        ->schema([
                            CuratorPicker::make('featured_image_id')
                                ->label(__('Blog::post.featured_image'))
                                ->relationship('featuredImage', 'id'),
                        ])->columnSpan(1),
                ]),
            self::getTranslationTab(),
        ];
    }

    protected static function setTranslationTab(string $locale): array
    {
        return PostTranslationForm::make($locale);
    }
}
