<?php

namespace App\Filament\Resources\Tag;

use Filament\Forms\Form;
use Domain\Tag\Models\Tag;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\Tag\TagResource\Pages;
use App\Filament\Resources\Tag\TagResource\Forms\TagForm;
use App\Filament\Resources\Tag\TagResource\Tables\TagTable;

class TagResource extends Resource
{
    protected static ?string $model = Tag::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    public static function getModelLabel(): string
    {
        return __('Tag::tag.singular');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Tag::tag.plural');
    }

    public static function form(Form $form): Form
    {
        return $form->schema(TagForm::make());
    }

    public static function table(Table $table): Table
    {
        return TagTable::make($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTagsPage::route('/'),
            'create' => Pages\CreateTagPage::route('/create'),
            'edit' => Pages\EditTagPage::route('/{record}/edit'),
        ];
    }
}
